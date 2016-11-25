<?php
/**
 * Created by PhpStorm.
 * User: russ
 * Date: 11/25/16
 * Time: 12:42 PM
 */

namespace Postindustria\Test;


final class Init
{
    const DSN = 'mysql:dbname=postindustria;host=localhost';
    const USER = 'russ';
    const PSWD = '';

    const RANDOM_ROWS_COUNT = 10;
    const RESULT_VALUES = ['normal', 'illegal', 'failed', 'success'];

    /**
     * @var $dbh PDO DB handle;
     */
    private $dbh;

    private function connect()
    {
        if (! $this->dbh) {
            try {
                $this->dbh = new \PDO(self::DSN, self::USER, self::PSWD);
            } catch (\PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }
        return true;
    }

    private function generateEnumForSql()
    {
        return "'" . implode("','", self::RESULT_VALUES) . "'";
    }

    private function execSQL(string $sql): bool
    {
        return $this->connect() && ($this->dbh->exec($sql) !== false);
    }

    /**
     * Creates table “test”
     */
    private function create(): bool
    {
        if (! $this->execSQL('drop table if exists test')) {
            return false;
        }

        $create_stmt = <<<SQL
          create table test (
            id int auto_increment primary key,
            script_name varchar(25) not null,
            start_time datetime,
            end_time datetime,
            `result` enum({$this->generateEnumForSql()})
          );
SQL;

        return $this->execSQL($create_stmt);
    }

    /**
     * Fills table with random data
     */
    private function fill()
    {
        $date_min = date_timestamp_get(date_create('2000-01-01'));
        $date_max = date_timestamp_get(date_create());
        $values = [];

        foreach(range(1, self::RANDOM_ROWS_COUNT) as $i) {
            $rand_key = array_rand(self::RESULT_VALUES);
            $values[] = sprintf("('%s', '%s', '%s', '%s')",
                    uniqid(),
                    date('Y-m-d H:i:s', random_int($date_min, $date_max)),
                    date('Y-m-d H:i:s', random_int($date_min, $date_max)),
                    self::RESULT_VALUES[$rand_key]
                );
        }

        $insert_sql = 'insert into test(script_name, start_time, end_time, result) values ' .
            implode(',', $values);

        $this->execSQL('delete from test');
        return $this->execSQL($insert_sql);
    }

    /**
     * Select data from table “test” where “result” is only “normal” or “success”
     *
     * @return array
     */
    public function get(): array
    {
        $result = [];

        if ($this->create() &&  $this->fill()) {
            $sql = "select * from test where result in ('normal', 'success')";
            foreach($this->dbh->query($sql) as $row) {
                $result[] = $row;
            }
        }

        return $result;
    }
}