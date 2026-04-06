<?php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use Dotenv\Dotenv;

/**
 * Esse comando testa a conexão da aplicação com o banco dados.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Felipe macedo <felipe.macedo@team.conexa.app>
 * @since 2.0
 */
class DatabaseController extends Controller
{
    /**
     * Esse comando testa a conexão da aplicação com o banco de dados
     * @return int Exit code
     */
    public function actionIndex()
    {
        try {

            // Attempt to get the active connection and execute a simple query
            $db = \Yii::$app->db;
            $db->open();

            if ($db->isActive) {
                // Test the query on the active connection
                $command = $db->createCommand('SELECT 1');
                $result = $command->queryScalar();

                if ($result !== false) {
                    $this->stdout("✓ Database connection successful!\n");
                    return ExitCode::OK;
                } else {
                    $this->stderr("✗ Database query failed.\n");
                    return ExitCode::UNSPECIFIED_ERROR;
                }
            } else {
                $this->stderr("✗ Could not establish a database connection.\n");
                return ExitCode::UNSPECIFIED_ERROR;
            }
        } catch (\Exception $e) {
            $this->stderr("✗ Database Error: " . $e->getMessage() . "\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }
       
    }
}