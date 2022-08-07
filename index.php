<?php

define('root_define', true); // определим константу для проверки источника скрипта

header('Content-Type: text/json; charset=utf-8'); // определяем тип контента

require_once 'engine/settings.php'; // подключаем настройки

require_once 'engine/database.php'; // подключаем базу данных
$db = new db($Config['db']['host'], $Config['db']['user'], $Config['db']['pass'], $Config['db']['name']); // инициализируем класс базы данных
$db->connect(); // подключаемся к базе данных



// Проверка запроса
$uri = $_SERVER['REQUEST_URI'];
$uri = explode('?', $uri);
$uri = explode('/', $uri[0]);

if (isset($_GET))
    $params = $_GET;
else
    $params = [];

function error($message)
{
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => $message]);
    exit;
}

// Проверка типа запроса
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET': // GET запросы

        // GET /api/socks (color, operation, cottonPart)
        if (isset($uri[1]) && $uri[1] == 'api') {
            if (isset($uri[2]) && $uri[2] == 'socks') {
                if (isset($_GET['color']) and isset($_GET['operation']) and isset($_GET['cottonPart'])) {

                    switch ($_GET['operation']) {
                        case 'moreThan':
                            $operation = '>=';
                            break;
                        case 'lessThan':
                            $operation = '<=';
                            break;
                        case 'equal':
                            $operation = '=';
                            break;

                        default:
                            error('Неверный тип операции');
                            break;
                    }

                    $result = $db->query("SELECT SUM(`quantity`) AS `quantity` FROM `socks` WHERE `color` = '" . $_GET['color'] . "' AND `cottonPart` " . $operation . " '" . $_GET['cottonPart'] . "'");

                    $row = $result->fetch_assoc();

                    echo json_encode($row);
                } else {
                    error('Недостаточно параметров');
                }
            } else {
                error('Неверный метод запроса');
            }
        } else {
            error('Неверный метод запроса');
        }

        break;

    case 'POST': // POST запросы

        // POST /api/socks/income (color, cottonPart, quantity)
        if (isset($uri[1]) && $uri[1] == 'api') {
            if (isset($uri[2]) && $uri[2] == 'socks') {
                if (isset($uri[3])) {
                    switch ($uri[3]) {
                        case 'income':
                            $data = json_decode(file_get_contents('php://input'), true);

                            // Проверка введенных данных
                            if (isset($data['color']) && isset($data['cottonPart']) && $data['cottonPart'] >= 0 && $data['cottonPart'] <= 100 && isset($data['quantity']) && $data['quantity'] >= 0) {
                                $result = $db->query("SELECT * FROM `socks` WHERE `color` = '" . $data['color'] . "' AND `cottonPart` = '" . $data['cottonPart'] . "'");
                                $row = $result->fetch_assoc();
                                if ($row) {
                                    $result = $db->query("UPDATE `socks` SET `quantity` = `quantity` + " . $data['quantity'] . " WHERE `color` = '" . $data['color'] . "' AND `cottonPart` = '" . $data['cottonPart'] . "'");
                                } else {
                                    $result = $db->query("INSERT INTO `socks` (`color`, `cottonPart`, `quantity`) VALUES ('" . $data['color'] . "', '" . $data['cottonPart'] . "', " . $data['quantity'] . ")");
                                }

                                if ($result) {
                                    echo json_encode(['Приход носков цвета ' . $data['color'] . ' в размере ' . $data['quantity'] . ' пар и процентом хлопка ' . $data['cottonPart'] . '% прошел успешно']);
                                } else {
                                    error('Ошибка при добавлении данных');
                                }
                            } else {
                                error('Недостаточно параметров');
                            }
                            break;

                        case 'outcome':
                            $data = json_decode(file_get_contents('php://input'), true);

                            // Проверка введенных данных
                            if (isset($data['color']) && isset($data['cottonPart']) && $data['cottonPart'] >= 0 && $data['cottonPart'] <= 100 && isset($data['quantity']) && $data['quantity'] >= 0) {
                                $result = $db->query("SELECT * FROM `socks` WHERE `color` = '" . $data['color'] . "' AND `cottonPart` = '" . $data['cottonPart'] . "'");
                                $row = $result->fetch_assoc();
                                if ($row) {
                                    if ($row['quantity'] >= $data['quantity']) {
                                        $result = $db->query("UPDATE `socks` SET `quantity` = `quantity` - " . $data['quantity'] . " WHERE `color` = '" . $data['color'] . "' AND `cottonPart` = '" . $data['cottonPart'] . "'");
                                        if ($result) {
                                            echo json_encode(['Отпуск носков цвета ' . $data['color'] . ' в размере ' . $data['quantity'] . ' пар и процентом хлопка ' . $data['cottonPart'] . '% прошел успешно']);
                                        } else {
                                            error('Ошибка при добавлении данных');
                                        }
                                    } else {
                                        error('Недостаточно количества на складе');
                                    }
                                } else {
                                    error('Нет данных в базе данных');
                                }
                            } else {
                                error('Недостаточно параметров');
                            }
                            break;

                        default:
                            error('Неверный метод запроса');
                            break;
                    }
                } else {
                    error('Неверный метод запроса');
                }
            } else {
                error('Неверный метод запроса');
            }
        } else {
            error('Неверный метод запроса');
        }

        break;

    default:
        header('HTTP/1.1 405 Method Not Allowed');
        break;
}

header('HTTP/1.1 200 OK');
