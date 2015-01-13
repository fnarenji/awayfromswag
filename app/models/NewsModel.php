<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/01/15
 * Time: 12:11
 */

namespace app\models;

use SwagFramework\mvc\Model;

class NewsModel extends Model
{

    /**
     * Return the content of new of the id
     * @param $id
     * @return array
     */
    public function getOneNews($id)
    {
        $sql = 'SELECT author,content FROM news WHERE idnews = ?';

        return DatabaseProvider::connection()->execute($sql, $id);
    }

    /**
     * Return all news
     * @return array
     */
    public function getNews()
    {
        $sql = 'SELECT author,content,date FROM news';

        return DatabaseProvider::connection()->execute($sql, null);
    }

    /**
     * Insert a news in DB
     * @param $author
     * @param $content
     * @param $date
     * @return bool
     */
    public function insertNews($author, $content, $date)
    {
        $sql = 'INSERT INTO news (`author`,`content`,`date`) VALUE ?,?,? ';

        DatabaseProvider::connection()->execute($sql, $author, $content, $date);

        return true;
    }

    /**
     * Delete a news
     * @param $id
     * @return bool
     */
    public function deleteNews($id)
    {
        $sql = 'DELETE FROM news WHERE idnews = ?';

        DatabaseProvider::connection()->execute($sql, $id);

        return true;

    }

    /**
     * Update a news
     * @param $id
     * @param $content
     * @param $date
     * @return bool
     */
    public function updateNews($id, $content, $date)
    {
        $sql = 'UPDATE news SET content = ?, date = ? WHERE idnews = ?';

        return DatabaseProvider::connection()->update($sql, $content, $date, $id);
    }


} 