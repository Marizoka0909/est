<?php
/**
 * SOS-Estudante - Rotas da Aplicação
 * Mapeia requisições para os respectivos Controllers e Métodos
 */

require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/DashboardController.php';
require_once __DIR__ . '/../controllers/SubjectController.php';
require_once __DIR__ . '/../controllers/TaskController.php';
require_once __DIR__ . '/../controllers/EventController.php';
require_once __DIR__ . '/../controllers/GradeController.php';
require_once __DIR__ . '/../controllers/HistoryController.php';
require_once __DIR__ . '/../controllers/ProfileController.php';

class Router {
    public static function dispatch(string $route, string $method): void {
        switch ($route) {
            // Autenticação
            case '':
            case 'home':
            case 'dashboard':
                (new DashboardController())->index();
                break;

            case 'login':
                $controller = new AuthController();
                if ($method === 'POST') {
                    $controller->login();
                } else {
                    $controller->showLogin();
                }
                break;

            case 'register':
                $controller = new AuthController();
                if ($method === 'POST') {
                    $controller->register();
                } else {
                    $controller->showRegister();
                }
                break;

            case 'logout':
                (new AuthController())->logout();
                break;

            // Disciplinas (Matérias)
            case 'subjects':
                (new SubjectController())->index();
                break;

            case 'subjects/store':
                (new SubjectController())->store();
                break;

            case 'subjects/update':
                (new SubjectController())->update();
                break;

            case 'subjects/delete':
                (new SubjectController())->delete();
                break;

            // Tarefas
            case 'tasks':
                (new TaskController())->index();
                break;

            case 'tasks/store':
                (new TaskController())->store();
                break;

            case 'tasks/update':
                (new TaskController())->update();
                break;

            case 'tasks/toggle':
                (new TaskController())->toggle();
                break;

            case 'tasks/delete':
                (new TaskController())->delete();
                break;

            // Eventos / Agenda
            case 'events':
                (new EventController())->index();
                break;

            case 'events/store':
                (new EventController())->store();
                break;

            case 'events/update':
                (new EventController())->update();
                break;

            case 'events/delete':
                (new EventController())->delete();
                break;

            // Notas / Boletim
            case 'grades':
                (new GradeController())->index();
                break;

            case 'grades/store':
                (new GradeController())->store();
                break;

            case 'grades/update':
                (new GradeController())->update();
                break;

            case 'grades/delete':
                (new GradeController())->delete();
                break;

            // Histórico e Auditoria
            case 'history':
                (new HistoryController())->index();
                break;

            // Perfil
            case 'profile':
                (new ProfileController())->index();
                break;

            case 'profile/update':
                (new ProfileController())->update();
                break;

            case 'profile/password':
                (new ProfileController())->updatePassword();
                break;

            // Rota 404
            default:
                http_response_code(404);
                require __DIR__ . '/../views/errors/404.php';
                break;
        }
    }
}
