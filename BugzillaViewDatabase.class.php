<?php
/**
 * @brief Implementation of BugzillaViewDatabase class
 * @file BugzillaView/BugzillaViewDatabase.class.php
 *
 * @author Dominique Barton (dbarton), confirm IT solutions GmbH
 */

/**
 * @brief   BugzillaView database class.
 *
 * The class will be used to connect to the bugzilla database.
 * It is implemented as a singleton pattern.
 */

class BugzillaViewDatabase
{
    /**
     * @brief   Singleton instance.
     */

    private static $instance = NULL;

    /**
     * @brief   PDO instance.
     */

    private $pdo = NULL;

    /**
     * @brief   Returns the singleton instance.
     */

    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            // globalize some vars (ugly thing!)
            global $wgBugzillaView, $wgParser, $wgScriptPath;

            // create instance
            self::$instance = new self;

            // now connect
            self::$instance->connect(
                $wgBugzillaView['dbDriver'],
                $wgBugzillaView['dbHost'],
                $wgBugzillaView['dbName'],
                $wgBugzillaView['dbUsername'],
                $wgBugzillaView['dbPassword']
            );
        }

        return self::$instance;
    }

    /**
     * @brief   Class constructor.
     *
     * Constructor is private, because we're using a singleton pattern.
     */

    private function __construct()
    {
    }

    /**
     * @brief   Connect to the database.
     *
     * @param   $driver     PDO driver
     * @param   $host       hostname or IP address
     * @param   $database   database name
     * @param   $username   database username
     * @param   $password   database password
     */

    public function connect($driver, $host, $database, $username, $password)
    {
        $this->disconnect();

        $this->pdo = new PDO(
            $driver.':'.host.'='.$host.';dbname='.$database,
            $username,
            $password,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }

    /**
     * @brief   Disconnect from the database.
     */

    public function disconnect()
    {
        $this->pdo = NULL;
    }

    /**
     * @brief   Query database.
     *
     * @param   $query          SQL query
     *
     * @retval  PDOStatement    PDO statement
     */

    public function query($query)
    {
        if(is_null($this->pdo))
            throw new Exception('Bugzilla database not connected');

        return $this->pdo->query($query);
    }
}
