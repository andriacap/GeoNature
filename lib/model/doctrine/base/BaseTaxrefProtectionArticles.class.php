<?php

/**
 * BaseTaxrefProtectionArticles
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $cd_protection
 * @property string $article
 * @property string $intitule
 * @property string $protection
 * @property string $arrete
 * @property string $fichier
 * @property integer $fg_afprot
 * @property string $niveau
 * @property integer $cd_arrete
 * @property string $url
 * @property integer $date_arrete
 * @property integer $rang_niveau
 * @property string $lb_article
 * @property string $type_protection
 * @property boolean $pn
 * 
 * @method string                   getCdProtection()    Returns the current record's "cd_protection" value
 * @method string                   getArticle()         Returns the current record's "article" value
 * @method string                   getIntitule()        Returns the current record's "intitule" value
 * @method string                   getProtection()      Returns the current record's "protection" value
 * @method string                   getArrete()          Returns the current record's "arrete" value
 * @method string                   getFichier()         Returns the current record's "fichier" value
 * @method integer                  getFgAfprot()        Returns the current record's "fg_afprot" value
 * @method string                   getNiveau()          Returns the current record's "niveau" value
 * @method integer                  getCdArrete()        Returns the current record's "cd_arrete" value
 * @method string                   getUrl()             Returns the current record's "url" value
 * @method integer                  getDateArrete()      Returns the current record's "date_arrete" value
 * @method integer                  getRangNiveau()      Returns the current record's "rang_niveau" value
 * @method string                   getLbArticle()       Returns the current record's "lb_article" value
 * @method string                   getTypeProtection()  Returns the current record's "type_protection" value
 * @method boolean                  getPn()              Returns the current record's "pn" value
 * @method TaxrefProtectionArticles setCdProtection()    Sets the current record's "cd_protection" value
 * @method TaxrefProtectionArticles setArticle()         Sets the current record's "article" value
 * @method TaxrefProtectionArticles setIntitule()        Sets the current record's "intitule" value
 * @method TaxrefProtectionArticles setProtection()      Sets the current record's "protection" value
 * @method TaxrefProtectionArticles setArrete()          Sets the current record's "arrete" value
 * @method TaxrefProtectionArticles setFichier()         Sets the current record's "fichier" value
 * @method TaxrefProtectionArticles setFgAfprot()        Sets the current record's "fg_afprot" value
 * @method TaxrefProtectionArticles setNiveau()          Sets the current record's "niveau" value
 * @method TaxrefProtectionArticles setCdArrete()        Sets the current record's "cd_arrete" value
 * @method TaxrefProtectionArticles setUrl()             Sets the current record's "url" value
 * @method TaxrefProtectionArticles setDateArrete()      Sets the current record's "date_arrete" value
 * @method TaxrefProtectionArticles setRangNiveau()      Sets the current record's "rang_niveau" value
 * @method TaxrefProtectionArticles setLbArticle()       Sets the current record's "lb_article" value
 * @method TaxrefProtectionArticles setTypeProtection()  Sets the current record's "type_protection" value
 * @method TaxrefProtectionArticles setPn()              Sets the current record's "pn" value
 * 
 * @package    faune
 * @subpackage model
 * @author     Gil Deluermoz
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTaxrefProtectionArticles extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('taxonomie.taxref_protection_articles');
        $this->hasColumn('cd_protection', 'string', 20, array(
             'type' => 'string',
             'primary' => true,
             'length' => 20,
             ));
        $this->hasColumn('article', 'string', 100, array(
             'type' => 'string',
             'length' => 100,
             ));
        $this->hasColumn('intitule', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('protection', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('arrete', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('fichier', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('fg_afprot', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('niveau', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('cd_arrete', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('url', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('date_arrete', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('rang_niveau', 'integer', 8, array(
             'type' => 'integer',
             'length' => 8,
             ));
        $this->hasColumn('lb_article', 'string', null, array(
             'type' => 'string',
             'length' => '',
             ));
        $this->hasColumn('type_protection', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('pn', 'boolean', 1, array(
             'type' => 'boolean',
             'length' => 1,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}