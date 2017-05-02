<?php
namespace Application\Service;

use Zend\Db\TableGateway\TableGateway;

class ArticleTagsManager
{
    public function getArticleTags(int $article_id = 0, string $locale = '')
    {
        $tags = [];
        $res = new TableGateway('tags', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->join('lang', 'lang.lang_id = tags.lang_id', ['locale']);
        $select->where(['tags.article_id' => $article_id]);
        if ($locale != '') {
            $select->where(['lang.locale' => $locale]);
        }
        $select->order('tags.tag_id ASC');
        $tags_tmp = $res->selectWith($select)->toArray();

        if (sizeof($tags_tmp) > 0) {
            foreach ($tags_tmp as $tag){
                $tags[$tag['locale']][] = $tag['tag_name'];
            }
        }

        return $tags;
    }

    public function deleteArticleTagsByArticleIdAndLangId(int $article_id = 0, int $lang_id = 0)
    {
        $res = new TableGateway('tags', $this->dbAdapter);
        $res->delete(['article_id' => $article_id, 'lang_id' => $lang_id]);
    }

    public function checkArticleTags(int $article_id = 0, int $lang_id = 0, array $tags_arr = []) : bool
    {
        $res = new TableGateway('tags', $this->dbAdapter);
        $sql = $res->getSql();
        $select = $sql->select();
        $select->where(['article_id' => $article_id]);
        $select->where(['lang_id' => $lang_id]);
        $select->order('tag_id ASC');
        $tags_tmp = $res->selectWith($select)->toArray();

        if (sizeof($tags_tmp) > 0) {
            foreach ($tags_tmp as $tag){
                $tags[] = $tag['tag_name'];
            }

            if (sizeof(array_diff($tags, $tags_arr)) > 0) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    public function setArticleTag(int $article_id = 0, int $lang_id = 0, string $tag_name = '')
    {
        $data = [
            'article_id'    => $article_id,
            'lang_id'       => $lang_id,
            'tag_name'      => $tag_name
        ];

        $res = new TableGateway('tags', $this->dbAdapter);
        $res->insert($data);
    }
}