<?php

/**
 * Class Database
 */
class Database
{
    private $dbconn;
    private $responseObj;

    /**
     * Database constructor.     
     */
    public function __construct(Response $responseObj)
    {
        $user               = "root";
        $password           = "1234qwer";
        $dbname             = "apijwt";
        $host               = "localhost";
        $this->responseObj  = $responseObj;

        try {
            $conn = new PDO('mysql:host=' .$host .';dbname=' . $dbname, $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbconn = $conn;
        } catch (PDOException $pdo) {
            $this->responseObj->setStatus(DATABASE_CONNECTION_FAILED);
            throw new PDOException($pdo->getMessage());            
        }
    }


    /**
     * @param string $jwtRefreshToken
     * @param int $user_id
     */
    public function saveRefreshToken(string $jwtRefreshToken, int $user_id)
    {
        $sql = 'INSERT INTO jwt_refresh_token  (token, user_id) VALUES(:token, :user_id) ON DUPLICATE KEY UPDATE token = "'.$jwtRefreshToken.'"';

			$stmt = $this->dbconn->prepare($sql);
			$stmt->bindParam(':token', $jwtRefreshToken);
			$stmt->bindParam(':user_id', $user_id);
			
			if($stmt->execute()) {
				return true;
			} else {
				return false;
			}
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function getUser(string $username, string $password)
    {
        $sql        = 'SELECT id, username, password FROM user WHERE username = :username AND password = :password';
        $stmt       = $this->dbconn->prepare($sql);
        $stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
        $stmt->execute();
        $userData    = $stmt->fetch(PDO::FETCH_OBJ);
        if (is_object($userData) && property_exists($userData, 'id')) {
            return $userData;
        } else {
            return false;
        }
    }

    /**
     * @param string $jwtRefreshToken   
     */
    public function getRefreshToken(string $jwtRefreshToken)
    {
        $sql        = 'SELECT token FROM jwt_refresh_token WHERE token = :token';
        $stmt       = $this->dbconn->prepare($sql);
        $stmt->bindParam(':token', $jwtRefreshToken);
		$stmt->execute();
        $token      = $stmt->fetch(PDO::FETCH_OBJ);
        if (is_object($token) && property_exists($token, 'token')) {
            return $token;
        } else {
            return false;
        }
    }

    /**
     * @param string $username
     * @param string $oldPassword
     * @param string $newPassword
     */
    public function changePassword($username, $oldPassword, $newPassword)
    {
        $sql        = 'UPDATE user SET password = :newPassword  WHERE username = :username && password = :oldPassword';
        $stmt       = $this->dbconn->prepare($sql);
        $stmt->bindParam(':username', $username);
		$stmt->bindParam(':newPassword', $newPassword);
        $stmt->bindParam(':oldPassword', $oldPassword);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

}