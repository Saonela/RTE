<?php
namespace RTER\ContentBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;

/**
 * @ES\Document(type="blogpost")
 */
class BlogPost
{
    /**
     * @var string
     *
     * @ES\Id()
     */
    public $id;

    /**
     * @var string
     *
     * @ES\Property(name="name", type="string")
     */
    public $name;
}