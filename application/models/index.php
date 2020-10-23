<?php

require_once "includes/variables.php";

/**
 * Пользователь GitHub
 */
class GitHubUser
{

    public static string $resource = "https://api.github.com/users/";

    private string $login;
    private int $id;
    private string $profileLink;
    private string $avatarLink;
    private string $type;
    private string $name;
    private string $email;
    private string $location;
    private bool $hireable;
    private string $bio;
    private int $publicRepos;
    private int $publicGists;
    private int $followers;
    private int $following;

    private DateTime $createdAt;
    private DateTime $updatedAt;

    public array $availableVars = [];

    /**
     * GitHubUser getter.
     *
     * @param $property
     *
     * @return mixed|null
     */
    public function __get($property)
    {
        return (property_exists($this, $property) &&
                in_array($property, $this->availableVars)) ?
            $this->$property : null;
    }

    /**
     * GitHubUser constructor.
     * Используется интерполяция для подстановки имен классов при объявлении.
     *
     * @param string $username
     * @param array  $availableVars Массив переменных, доступных для обращения.
     */
    public function __construct($username, $availableVars)
    {
        session_start();

        // если время последнего обращения (из куки) было больше чем час назад
        // либо неоткуда брать данные (нет инфы в сессии или метки времени в куки)
        // делаем запрос по API и записываем в сессию
        if ((isset($_COOKIE["GitHubLastRequest"]) &&
             (time() - intval($_COOKIE["GitHubLastRequest"])) > 3600) ||
            (! isset($_SESSION["GitHubUser"]) ||
             (! isset($_COOKIE["GitHubLastRequest"])))
        ) {
            $user = self::requestInfo($username);
            $_SESSION["GitHubUser"] = json_encode($user);
        } // иначе достаем из сессии данные
        else {
            $user = json_decode($_SESSION["GitHubUser"], true);
        }

        // время обращения будет храниться в куки час. больше нет смысла, т.к.
        // через час делается новый запрос по API
        setcookie("GitHubLastRequest", time(), time() + 3600);

        foreach ($availableVars as $jsonKey => $variable) {
            if ($variable["type"] != "DateTime") {
                // явное приведение к типу, переданному в виде строки
                settype($user[$jsonKey], $variable["type"]);

                if ($this->isValid($jsonKey, $user[$jsonKey])) {
                    // например, $this->login = $user["login"]
                    $this->{$variable["classField"]} =
                        htmlspecialchars($user[$jsonKey]);
                    $this->availableVars[] = $variable["classField"];
                }
            } else {
                try {
                    // например, $this->createdAt = new DateTime("2018-10-24T07:30:44Z")
                    $this->{$variable["classField"]} =
                        new $variable["type"]($user[$jsonKey]);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    /**
     * Отправка HTTP-запроса с использованием cURL.
     *
     * @param string $username Логин пользователя GitHub
     *
     * @return mixed
     */
    public static function requestInfo($username)
    {
        $ch = curl_init(self::$resource . $username);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
                    [
                        "Accept: application/vnd.github.v3+json",
                        "Content-Type: text/plain",
                        "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36"
                    ]);
        $html = curl_exec($ch);
        $json = json_decode($html, true);
        curl_close($ch);

        return $json;
    }

    /**
     * Проверка определенного поля на валидность.
     *
     * @param string $jsonKey Ключ поля
     * @param mixed  $value   Значение поля
     *
     * @return bool
     */
    private function isValid($jsonKey, $value)
    {
        $isSuccess = true;

        switch ($jsonKey) {
            case "id":
                if (! is_int($value) || $value <= 0) {
                    $isSuccess = false;
                }
                break;
            case "html_url":
            case "avatar_url":
                if (! filter_var($value, FILTER_VALIDATE_URL)) {
                    $isSuccess = false;
                }
                break;
            case "email":
                if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $isSuccess = false;
                }
                break;
            case "public_repos":
            case "public_gists":
            case "followers":
            case "following":
                if (! is_int($value) || $value < 0) {
                    $isSuccess = false;
                }
                break;
        }

        // если ключ не попал ни в один кейс, то вернется true

        return $isSuccess;
    }

    function printName()
    {
        echo "User's name is $this->name";
    }

}

$username = "arteemkuznetsov";

$user = new GitHubUser($username, $variables);

/*
$google = GitHubUser::requestInfo("google");

/*
$func = "printName";
$user->$func();
*/

require_once "application/views/index.php";
