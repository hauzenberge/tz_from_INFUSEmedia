<?php
class banner
{
    protected $config = [
        'host' => 'localhost',
        'db' => 'tz',
        'user' => 'root',
        'pass' => '26041995'
    ];

    public function search($ip_adress)
    {
        $mysqli = new mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db']);
        $result = $mysqli->query("SELECT id, ip_address, user_agent, view_date, page_url, views_count FROM visitors
        WHERE ip_address = '" . $ip_adress . "' ");

        return $result;
    }

    public function create($ip_adress, $user_agent, $url)
    {
        $mysqli = new mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db']);
        $result = $mysqli->query("INSERT INTO `visitors` (`ip_address`, `user_agent`, `view_date`, `page_url`, `views_count`)
        VALUES ('" . $ip_adress . "', '" . $user_agent . "', '" . date('d-m-Y h:i:s') . "', '" . $url . "', 0);");

        return $result;
    }

    public function update_count($ip_adress, $user_agent, $count)
    {
        $mysqli = new mysqli($this->config['host'], $this->config['user'], $this->config['pass'], $this->config['db']);
        $result = $mysqli->query("UPDATE `visitors` SET
        `ip_address` = '" . $ip_adress . "',
        `user_agent` = '" . $user_agent . "',
        `views_count` = '" . $count . "'
        WHERE `ip_address` = '" . $ip_adress . "';");
    }

    public function visitor($ip_adress, $user_agent)
    {
        $result = $this->search($ip_adress);
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        if ($result->num_rows == 0) {
            $this->create($ip_adress, $user_agent, $url);
            $result = $this->search($ip_adress);
        }

        return $result->fetch_assoc();
    }
}
