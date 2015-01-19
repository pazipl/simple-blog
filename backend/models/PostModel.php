<?php

class PostModel {

    protected $_title;
    protected $_description;
    protected $_image;
    protected $_convertedImageName = null;
    protected $_errorMessages = [];

    const IMAGE_MAX_SIZE = 2097152; // 2 * 1024 * 1024;

    public function __construct () {}

    public function setTitle ($inputTitle) {
        $this->_title = $inputTitle;
    }

    public function getTitle () {
        return $this->_title;
    }

    public function setDescription ($inputDescription) {
        $this->_description = $inputDescription;
    }

    public function getDescription () {
        return $this->_description;
    }

    public function setImage ($inputImage) {
        $this->_image = $inputImage;
    }

    public function getImage () {
        return $this->_image;
    }

    public function getErrorMessages () {
        return $this->_errorMessages;
    }

    public function processForm () {
        $image = $this->getImage();

        if (!$this->getTitle() || !$this->getDescription()) {
            $this->_errorMessages[] = 'Proszę wypełnić wszystkie wymagane pola';
        }

        if (!empty($image['name']) && array_search($image['type'], ['image/jpeg', 'image/png'])) {
            $this->_errorMessages[] = 'Wybrany plik ma niepoprawny format graficzny. Dopuszczalne typy to png i jpeg';
        }

        if (!empty($image['name']) && ($image['size'] > self::IMAGE_MAX_SIZE || $image['error'] === UPLOAD_ERR_INI_SIZE)) {
            $this->_errorMessages[] = 'Wybrany plik jest za duży. Maksymalny rozmiar to 2MB';
        }

        if (empty($this->getErrorMessages()) && $image['name']) {
            $this->proccessImage();
        }

        return empty($this->getErrorMessages()) ? $this->saveNewPost() : false;
    }

    public static function countAll ($params = []) {
        $pdo = DBModel::getInstance();
        $sqlQuery = 'SELECT COUNT(id) FROM post ';

        if (!empty($params) && isset($params['searchQuery']) && $params['searchQuery'] !== '') {
            $sqlQuery .= ' WHERE MATCH(title, description) AGAINST("' . $params['searchQuery'] . '"  IN BOOLEAN MODE) ';
        }

        $sqlQuery .= ' ORDER BY id';

        $query = $pdo->query($sqlQuery);


        return $query->fetchColumn();
    }

    public static function deleteById($id) {
        $id = (int) $id;
        $pdo = DBModel::getInstance();
        $sqlQuery = 'DELETE FROM post WHERE id = ' . $id;

        $post = self::findOne($id);

        if ($post && $post['thumb']) {
            self::removeImage($post['thumb']);
        }

        return $pdo->query($sqlQuery)->execute();
    }

    public static function findOne ($id) {
        $id = (int) $id;
        $pdo = DBModel::getInstance();
        $sqlQuery = 'SELECT id, title, description, thumb, publish_date FROM post WHERE id = ' . $id;

        $query = $pdo->query($sqlQuery);

        return is_object($query) ? $query->fetch() : [];
    }

    public static function findAll ($params = []) {
        $pdo = DBModel::getInstance();
        $sqlQuery = 'SELECT id, title, description, thumb, publish_date FROM post';

        if (!empty($params) && isset($params['searchQuery']) && $params['searchQuery'] !== '') {
            $sqlQuery .= ' WHERE MATCH(title, description) AGAINST("' . $params['searchQuery'] . '" IN BOOLEAN MODE) ';
        }

        if (!empty($params) && isset($params['offsetStart']) && isset($params['perPage'])) {
            $sqlQuery .= ' LIMIT ' . $params['offsetStart'] . ', ' . $params['perPage'];
        }

        $query = $pdo->query($sqlQuery);

        return is_object($query) ? $query->fetchAll() : [];
    }

    public static function removeImage ($file) {
        $thumb = $file;
        $thumb_mini = str_replace('.', '_thumb.', $thumb);

        unlink(UPLOAD_IMAGE_PATH . $thumb);
        unlink(UPLOAD_IMAGE_PATH . $thumb_mini);
    }


    protected function proccessImage () {
        $inputImage = $this->getImage();
        $inputTmpName = $inputImage['tmp_name'];
        $inputName = $inputImage['name'];
        $inputType = $inputImage['type'];

        $ext = strtolower(pathinfo($inputName, PATHINFO_EXTENSION));

        $targetName = md5($inputTmpName) . '.' . $ext;
        $targetLargeImagePath = UPLOAD_IMAGE_PATH . $targetName;
        $targetThumbImagePath = UPLOAD_IMAGE_PATH . str_replace('.' . $ext, '_thumb.' . $ext, $targetName);

        $this->resizeImage($inputTmpName, $targetLargeImagePath, $inputType, 200, 200);
        $this->resizeImage($inputTmpName, $targetThumbImagePath, $inputType, 100, 100);

        $this->_convertedImageName = $targetName;
    }

    protected function saveUpdatedPost () {

    }

    protected function saveNewPost () {

        $pdo = DBModel::getInstance();
        $prepareQuery = $pdo->prepare('INSERT INTO post(title, description, thumb) VALUES (:title, :description, :thumb)');

        $prepareQuery->bindValue(':title', $this->getTitle(), PDO::PARAM_STR);
        $prepareQuery->bindValue(':description', $this->getDescription(), PDO::PARAM_STR);

        $thumb = $this->_convertedImageName;

        $prepareQuery->bindValue(':thumb', $thumb, PDO::PARAM_STR);

        $save = $prepareQuery->execute();

        if ($save === 0) {
            $this->_errorMessages[] = 'Wystąpił błąd podczas zapisywania danych do bazy.';
            return false;
        }

        return (bool) $save;

    }

    protected function resizeImage($tmpName, $targetPath, $imageType, $width, $height) {
        /* Get original image x y*/
        list($w, $h) = getimagesize($tmpName);
        /* calculate new image size with ratio */
        $ratio = max($width/$w, $height/$h);
        $h = ceil($height / $ratio);
        $x = ($w - $width / $ratio) / 2;
        $w = ceil($width / $ratio);

        /* read binary data from image file */
        $imgString = file_get_contents($tmpName);
        /* create image from string */
        $image = imagecreatefromstring($imgString);
        $tmp = imagecreatetruecolor($width, $height);

        imagecopyresampled($tmp, $image, 0, 0, $x, 0, $width, $height, $w, $h);

        /* Save image */
        switch ($imageType) {
            case 'image/jpeg':
                imagejpeg($tmp, $targetPath, 100);
                break;
            case 'image/png':
                imagepng($tmp, $targetPath, 0);
                break;
        }

        /* cleanup memory */
        imagedestroy($image);
        imagedestroy($tmp);

    }

}