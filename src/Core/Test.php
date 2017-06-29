<?php
/**
 * VisualPHPUnit
 *
 * VisualPHPUnit is a visual front-end for PHPUnit.
 *
 * PHP Version 5.6<
 *
 * @author    Johannes Skov Frandsen <localgod@heaven.dk>
 * @copyright 2011-2016 VisualPHPUnit
 * @license   http://opensource.org/licenses/BSD-3-Clause The BSD License
 * @link      https://github.com/VisualPHPUnit/VisualPHPUnit VisualPHPUnit
 */
namespace Visualphpunit\Core;

use \Doctrine\DBAL\Connection;
use \DateTime;

/**
 * Visualphpunit test result
 *
 * @author Johannes Skov Frandsen <localgod@heaven.dk>
 */
class Test
{

    /**
     * When returning a list of test group them by hour
     *
     * @var string
     */
    const GROUP_BY_HOUR = '%Y-%m-%d %H';

    /**
     * When returning a list of test group them by day
     *
     * @var string
     */
    const GROUP_BY_DAY = '%Y-%m-%d';

    /**
     * When returning a list of test group them by month
     *
     * @var string
     */
    const GROUP_BY_MONTH = '%Y-%m';

    /**
     * When returning a list of test group them by year
     *
     * @var string
     */
    const GROUP_BY_YEAR = '%Y';

    /**
     * Create the table if it dos not exists
     *
     * @param \Doctrine\DBAL\Connection $connection
     *
     * @return boolean
     */
    public static function createTable(Connection $connection)
    {
        $sql = "CREATE TABLE IF NOT EXISTS tests(
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT,
            class TEXT,
            status TEXT,
            executed NUMERIC);";
        $stmt = $connection->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Drop the table if it exists
     *
     * @param \Doctrine\DBAL\Connection $connection
     *
     * @return boolean
     */
    public static function dropTable(Connection $connection)
    {
        $sql = "DROP tests;";
        $stmt = $connection->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Truncate the table
     *
     * @param \Doctrine\DBAL\Connection $connection
     *
     * @return boolean
     */
    public static function truncateTable(Connection $connection)
    {
        $sql = "DELETE FROM tests;";
        $stmt = $connection->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Store a test suite result
     *
     * @param \Doctrine\DBAL\Connection $connection
     * @param mixed[] $result
     *
     * @return boolean
     */
    public static function store(Connection $connection, $result)
    {
        $sql = "INSERT INTO tests (name, class, status, executed) VALUES (?, ?, ?, ?);";
        $date = new DateTime();

        foreach ($result['tests'] as $test) {
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(1, $test['name']);
            $stmt->bindValue(2, $test['class']);
            $stmt->bindValue(3, $test['status']);
            $stmt->bindValue(4, $date->format('Y-m-d H:i:s'));
            $stmt->execute();
        }
    }

    /**
     * Get test per day between dates
     *
     * @param \Doctrine\DBAL\Connection $connection
     * @param \DateTime $start
     * @param \DateTime $end
     * @param String $unit
     *
     * @return mixed[]
     */
    public static function getTests(Connection $connection, DateTime $start, DateTime $end, $unit)
    {
        $sql = "
            SELECT
            strftime('%Y-%m-%d %H:%M:%S', executed) as datetime,
            ltrim(strftime('" . $unit . "', executed), '0') as unit,
            COUNT() as number,
            status
            FROM tests
            WHERE executed BETWEEN datetime(?) AND datetime(?)
            AND status = 'passed'
            GROUP BY strftime('" . $unit . "', executed)
            UNION
            SELECT
            strftime('%Y-%m-%d %H:%M:%S', executed) as datetime,
            ltrim(strftime('" . $unit . "', executed), '0') as unit,
            COUNT() as number,
            status
            FROM tests
            WHERE executed BETWEEN datetime(?) AND datetime(?)
            AND status = 'failed'
            GROUP BY strftime('" . $unit . "', executed)
            UNION
            SELECT
            strftime('%Y-%m-%d %H:%M:%S', executed) as datetime,
            ltrim(strftime('" . $unit . "', executed), '0') as unit,
            COUNT() as number,
            status
            FROM tests
            WHERE executed BETWEEN datetime(?) AND datetime(?)
            AND status = 'notImplemented'
            GROUP BY strftime('" . $unit . "', executed)
            UNION
            SELECT
            strftime('%Y-%m-%d %H:%M:%S', executed) as datetime,
            ltrim(strftime('" . $unit . "', executed), '0') as unit,
            COUNT() as number,
            status
            FROM tests
            WHERE executed BETWEEN datetime(?) AND datetime(?)
            AND status = 'skipped'
            GROUP BY strftime('" . $unit . "', executed)
            UNION
            SELECT
            strftime('%Y-%m-%d %H:%M:%S', executed) as datetime,
            ltrim(strftime('" . $unit . "', executed), '0') as unit,
            COUNT() as number,
            status
            FROM tests
            WHERE executed BETWEEN datetime(?) AND datetime(?)
            AND status = 'error'
            GROUP BY strftime('" . $unit . "', executed)
            ORDER BY status";

        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, $start->format('Y-m-d H:i:s'));
        $stmt->bindValue(2, $end->format('Y-m-d H:i:s'));
        $stmt->bindValue(3, $start->format('Y-m-d H:i:s'));
        $stmt->bindValue(4, $end->format('Y-m-d H:i:s'));
        $stmt->bindValue(5, $start->format('Y-m-d H:i:s'));
        $stmt->bindValue(6, $end->format('Y-m-d H:i:s'));
        $stmt->bindValue(7, $start->format('Y-m-d H:i:s'));
        $stmt->bindValue(8, $end->format('Y-m-d H:i:s'));
        $stmt->bindValue(9, $start->format('Y-m-d H:i:s'));
        $stmt->bindValue(10, $end->format('Y-m-d H:i:s'));
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
