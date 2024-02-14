<?php

/*
<!#CR>
 ************************************************************************************************************************
 *                                                    Copyrigths ©                                                      *
 * -------------------------------------------------------------------------------------------------------------------- *
 *          Authors Names    > Jimmy Nogherot                                                                           *
 *          Authors Email    > jimmy@tabularasa.fr                                                                      *
 *          Company Name     > Tabularasa                                                                               *
 *          Company Email    > jimmy@tabularasa.fr                                                                      *
 *          Company Websites > https://tabularasa.fr                                                                    *
 * -------------------------------------------------------------------------------------------------------------------- *
 *                                           File and License Informations                                              *
 * -------------------------------------------------------------------------------------------------------------------- *
 *          File Name        > <!#FN> TypeChamp.php </#FN>
 *          File Birth       > <!#FB> 2023/10/26 11:09:08.151 </#FB>                                                    *
 *          File Mod         > <!#FT> 2023/12/12 14:31:56.087 </#FT>                                                    *
 *          License          > <!#LT> BSD-3-Clause-Attribution </#LT>
 *                             <!#LU> https://spdx.org/licenses/BSD-3-Clause-Attribution.html </#LU>
 *                             <!#LD> This file may not be redistributed in whole or significant part. </#LD>
 *          File Version     > <!#FV> 1.0.2 </#FV>
 *                                                                                                                      *
 ******************************************* VSCode Extension: Version Boss *********************************************
</#CR>
 */

namespace trdev\ContaoTypechampBundle\Classes;

class TypeChamp extends \Backend
{
    #region Texte
    /**
     * text
     * Créer un champ Texte en lien avec une table existante
     * @param bool $obligatoire
     * @param string $classe w50/clr
     * @param array $callback par exemple [[$t,'toUpper']]
     * @return array
     */
    public static function text($obligatoire = false, $classe = 'w50', $callback = [])
    {
        $item = array(
            'inputType' => 'text',
            'search'    => true,
            'eval'      => array('maxlength' => 255, 'tl_class' => $classe, 'mandatory' => $obligatoire),
            'sql'       => "varchar(255) NOT NULL default ''",
        );

        if (Count($callback) > 0) {
            $item['save_callback'] = $callback;
        }

        return $item;
    }
    #endregion

    #region Number
    /**
     * number
     * Créer un champ numérique en lien avec une table existante
     * @param bool $obligatoire
     * @return array
     */
    public static function number($obligatoire = false)
    {
        $item = array(
            'inputType' => 'text',
            'search'    => true,
            'eval'      => array('maxlength' => 255, 'rgxp' => 'digit', 'tl_class' => 'w50', 'mandatory' => $obligatoire),
            'sql'       => "float(10) unsigned NOT NULL default '0'",
        );

        return $item;
    }
    #endregion

    #region TexteArea
    /**
     * textarea
     * Créer un champ Select en lien avec une table existante
     * @param bool $tinyMce: utiliser tinyMce ou pas
     * @param bool $obligatoire
     * @return array
     */
    public static function textarea($tinyMce = false, $obligatoire = false)
    {
        $item = array(
            'inputType'     => 'textarea',
            'eval'          => array(
                'tl_class'  => 'clr',
                'mandatory' => $obligatoire,
            ),
            'load_callback' => array(array('trdev\ContaoTypechampBundle\Classes\TypeChamp', 'convertAbsoluteLinks')),
            'save_callback' => array(array('trdev\ContaoTypechampBundle\Classes\TypeChamp', 'convertRelativeLinks')),
            'sql'           => "mediumtext NULL",
        );

        if ($tinyMce === true) {
            $item['eval']['rte']        = 'tinyNews';
            $item['eval']['helpwizard'] = true;
        }

        return $item;
    }
    #endregion

    #region Switch
    /**
     * ouiNon
     * Créer un champ avec juste une case a coché, fait office de Oui/Non
     * @param bool $obligatoire
     * @param int $default 0 ou 1
     * @return array
     */
    public function ouiNon($obligatoire = false, $default = '0')
    {
        $item = array(
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default '" . $default . "'",
            'eval'      => array('tl_class' => 'clr', 'mandatory' => $obligatoire),
        );

        return $item;
    }
    #endregion

    #region Date
    /**
     * date
     * Créer un champ avec juste une case a coché, fait office de Oui/Non
     * @param bool $heures On a les heures ou pas ?
     * @param bool $obligatoire
     * @param bool $classe w50/clr
     * @return array
     */
    public static function date($heures = false, $obligatoire = false, $classe = 'w50')
    {
        $rgxp = ($heures === true) ? 'datim' : 'date';
        $item = array(
            'inputType' => 'text',
            'eval'      => array('rgxp' => $rgxp, 'datepicker' => true, 'tl_class' => $classe, 'mandatory' => $obligatoire),
            'sql'       => "varchar(11) NOT NULL default ''",
        );

        return $item;
    }
    #endregion

    #region Select a partir d'une table
    /**
     * selectTable
     * Créer un champ Select en lien avec une table existante
     * @param string $foreignKey tl_article.name
     * @param bool $multiple
     * @param bool $includeBlankOption
     * @param bool $obligatoire
     * @return array
     */
    public static function selectTable($foreignKey = '', $multiple = false, $includeBlankOption = false, $obligatoire = false)
    {
        $item = array(
            'inputType'  => 'select',
            'filter'     => true,
            'foreignKey' => $foreignKey,
            'sql'        => ($multiple === true) ? "blob NULL" : "int(10) unsigned NOT NULL default '0'",
            'eval'       => array('chosen' => true, 'includeBlankOption' => $includeBlankOption, 'multiple' => $multiple, 'tl_class' => 'w50', 'mandatory' => $obligatoire),
            'relation'   => array('type' => 'hasOne', 'load' => 'lazy'),
        );

        return $item;
    }
    #endregion

    #region Select
    /**
     * select
     * Créer un champ Select
     * @param array $options Liste des options dans un tableau (avec ou sans key)
     * @param bool $multiple
     * @param bool $includeBlankOption
     * @param bool $obligatoire
     * @param bool $autoSubmit Si on utilise des subpalettes
     * @return array
     */
    public static function select($options, $multiple = false, $includeBlankOption = false, $obligatoire = false, $autoSubmit = false)
    {
        $item = array(
            'inputType' => 'select',
            'filter'    => true,
            'options'   => $options,
            'sql'       => ($multiple === true) ? "blob NULL" : "varchar(255) NOT NULL default ''",
            'eval'      => array('chosen' => true, 'includeBlankOption' => $includeBlankOption, 'multiple' => $multiple, 'tl_class' => 'w50', 'mandatory' => $obligatoire, 'submitOnChange' => $autoSubmit),
        );

        return $item;
    }
    #endregion

    #region SelectCallback
    /**
     * Créer un champ Select avec des données à partir d'un callback
     * @param array $options Liste des options array('maClasse','MaFonction')
     * @param bool $multiple
     * @param bool $includeBlankOption
     * @param bool $obligatoire
     * @param bool $autoSubmit Si on utilise des subpalettes
     * @return array
     */
    public static function selectCallback($options, $multiple = false, $includeBlankOption = false, $obligatoire = false, $autoSubmit = false)
    {
        $item = array(
            'inputType'        => 'select',
            'filter'           => true,
            'options_callback' => $options,
            'sql'              => ($multiple === true) ? "blob NULL" : "varchar(255) NOT NULL default ''",
            'eval'             => array('chosen' => true, 'includeBlankOption' => $includeBlankOption, 'multiple' => $multiple, 'tl_class' => 'w50', 'mandatory' => $obligatoire, 'submitOnChange' => $autoSubmit),
        );

        return $item;
    }
    #endregion

    #region Checkboxs
    /**
     * checkbox
     * Créer un champ Checkbox
     * @param array $options Liste des options dans un tableau (avec ou sans key)
     * @return array
     */
    public static function checkbox($options)
    {
        $item = array(
            'inputType' => 'checkbox',
            'options'   => $options,
            'eval'      => array(
                'multiple' => true,
            ),
            'sql'       => 'blob NULL',
        );

        return $item;
    }

    /**
     * checkboxTable
     * Créer un champ Checkbox en lien avec une table
     * @param string $options tl_article.name
     * @return array
     */
    public static function checkboxTable($options)
    {
        $item = array(
            'inputType'  => 'checkbox',
            'foreignKey' => $options,
            'eval'       => array(
                'multiple' => true,
            ),
            'sql'        => 'blob NULL',
        );

        return $item;
    }

    /**
     * checkboxCallback
     * Créer un champ Checkbox qui récupère les options à partir d'une function
     * @param string $options array('maClasse','MaFonction')
     * @return array
     */
    public static function checkboxCallback($options)
    {
        $item = array(
            'inputType'        => 'checkbox',
            'options_callback' => $options,
            'eval'             => array(
                'multiple' => true,
            ),
            'sql'              => 'blob NULL',
        );

        return $item;
    }
    #endregion

    #region Fichiers
    /**
     * fichier
     * @param bool $multiple
     * @param bool $obligatoire
     * @return array
     */
    public static function fichier($multiple = false, $obligatoire = false)
    {
        $item = array(
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => [
                'tl_class'   => 'clr',
                'mandatory'  => $obligatoire,
                'fieldType'  => ($multiple === true) ? 'checkbox' : 'radio',
                'multiple'   => $multiple,
                'files'      => true,
                'extensions' => \Contao\Config::get('uploadTypes'),
            ],
            'sql'       => "blob NULL",
        );
        return $item;
    }
    #endregion

    #region Dossier
    /**
     * dossier
     * @param bool $multiple
     * @param bool $obligatoire
     * @return array
     */
    public static function dossier($multiple = false, $obligatoire = false)
    {
        $item = array(
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => [
                'tl_class'  => 'clr',
                'mandatory' => $obligatoire,
                'fieldType' => ($multiple === true) ? 'checkbox' : 'radio',
                'multiple'  => $multiple,
                'files'     => false,
            ],
            'sql'       => "blob NULL",
        );
        return $item;
    }
    #endregion

    #region Multicolumn
    /**
     * Multi Column Wizard
     * @param string $table tl_article
     * @param string $champ nomChamp
     * @param array $cols array('annee' => 'text', 'id' => 'text')
     * @param array $options
     * @return array
     */
    public static function mcw($table, $champ, $cols, $options = false)
    {
        $item = array(
            'exclude'   => true,
            'inputType' => 'multiColumnWizard',
            'eval'      => array
            (
                'tl_class' => 'clr',
            ),
            'sql'       => "blob NULL",
        );

        foreach ($cols as $key => $value) {
            $val = array(
                'label' => &$GLOBALS['TL_LANG'][$table][$champ][$key],
            );
            switch ($value) {
                case 'date':
                    $val['inputType'] = 'text';
                    $val['eval']      = array(
                        'rgxp'       => 'date',
                        'datepicker' => true,
                        'tl_class'   => 'wizard',
                    );
                    break;
                case 'select':
                    $val['inputType'] = 'select';
                    $val['options']   = $options[$key];
                    $var['eval']      = array(
                        'includeBlankOption' => true,
                    );
                    break;
                case 'selectTable':
                    $val['inputType']  = 'select';
                    $val['foreignKey'] = $options[$key];
                    $var['eval']       = array(
                        'includeBlankOption' => true,
                    );
                    break;

                default:
                    $val['inputType'] = $value;
                    break;
            }
            $item['eval']['columnFields'][$key] = $val;
        }
        return $item;
    }
    #endregion

    #region List Wizard
    /**
     * listWizard
     *
     * @return array
     */
    public static function listWizard()
    {
        $item = array(
            'inputType' => 'listWizard',
            'eval'      => array(
                'maxlength' => 255,
                'allowHtml' => false,
            ),
            'sql'       => 'blob NULL',
        );

        return $item;
    }
    #endregion

    #region Alias
    /**
     * alias
     *
     * @param  string $table = le nom de la table en cours vers laquelle faire pointer le generateAlias
     * @param string $fonction = la fonction appellée (utilie quand on a 2 type d'alias différents)
     * @return array
     */
    public static function alias($table, $fonction = 'generateAlias')
    {
        $item = array(
            'inputType'     => 'text',
            'search'        => true,
            'sql'           => "varchar(255) NOT NULL default ''",
            'eval'          => array('rgxp' => 'alias', 'doNotCopy' => true, 'unique' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'save_callback' => array
            (
                array($table, $fonction),
            ),
        );

        return $item;
    }
    #endregion

    #region PageTree

    /**
     * Selecteur de page
     *
     * @param  boolean $obligatoire
     * @return void
     */
    public static function pageTree($obligatoire = false)
    {
        return array(
            'inputType'  => 'pageTree',
            'foreignKey' => 'tl_page.title',
            'eval'       => array('fieldType' => 'radio', 'mandatory' => $obligatoire, 'tl_class' => 'w50'),
            'sql'        => "int(10) unsigned NOT NULL default 0",
            'relation'   => array('type' => 'hasOne', 'load' => 'lazy'),
        );

    }
    #endregion

    #region Traductions = Ajouter les traductions de base, commune a une grande majorité des tables
    /**
     * traduction
     * Ajoute toute les traductions de base qui sont généralement communes.
     *
     * @param  string $t = nom de la table
     * @return void
     */
    public static function traductions($t)
    {
        $GLOBALS['TL_LANG'][$t]['name'][0]      = "Nom";
        $GLOBALS['TL_LANG'][$t]['alias'][0]     = "Alias";
        $GLOBALS['TL_LANG'][$t]['tstamp'][0]    = "Date de création";
        $GLOBALS['TL_LANG'][$t]['published'][0] = "Publié ?";

        $GLOBALS['TL_LANG'][$t]['system'] = "Champs système";

        $GLOBALS['TL_LANG'][$t]['new']     = array('Nouveau ', 'Ajouter');
        $GLOBALS['TL_LANG'][$t]['show']    = array(' détails', 'Montrer les détails  ID %s');
        $GLOBALS['TL_LANG'][$t]['edit']    = array('Editer ', 'Editer  ID %s');
        $GLOBALS['TL_LANG'][$t]['cut']     = array('Déplacer ', 'Déplacer  ID %s');
        $GLOBALS['TL_LANG'][$t]['copy']    = array('Dupliquer ', 'Dupliquer  ID %s');
        $GLOBALS['TL_LANG'][$t]['delete']  = array('Effacer ', 'Effacer  ID %s');
        $GLOBALS['TL_LANG'][$t]['toggle']  = array('Publier ', 'Publier  ID %s');
        $GLOBALS['TL_LANG'][$t]['feature'] = array('Mise en avant ', 'Mettre en avant ID %s');
    }
    #endregion

    /**
     * toggleButton
     *  le code pour appeller le bouton toggle dans les Operations
     * @param  string $table
     * @return array
     */
    public static function toggleButton($table)
    {
        return array(
            'label'           => &$GLOBALS['TL_LANG'][$table]['toggle'],
            'icon'            => 'visible.gif',
            'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
            'button_callback' => array($table, 'toggleIcon'),
        );
    }

    /**
     * toggleIcon
     * Dans la table parent, appellé comme suit :
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
    return TypeChamp::toggleIcon($this, $row, $href, $label, $title, $icon, $attributes);
    }
     *
     * @param  [type] $table
     * @param  [type] $row
     * @param  [type] $href
     * @param  [type] $label
     * @param  [type] $title
     * @param  [type] $icon
     * @param  [type] $attributes
     * @return void
     */
    public function toggleIcon($table, $row, $href, $label, $title, $icon, $attributes)
    {
        $table->import('BackendUser', 'User');
        if (strlen($table->Input->get('tid'))) {
            $table->toggleVisibility($table->Input->get('tid'), ($table->Input->get('state') == 0));
            $table->redirect($table->getReferer());
        }

        $href .= '&amp;id=' . $table->Input->get('id') . '&amp;tid=' . $row['id'] . '&amp;state=' . $row[''];
        if (!$row['published']) {
            $icon = 'invisible.gif';
        }
        return '<a href="' . $table->addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . $table->generateImage($icon, $label) . '</a> ';
    }

    /**
     * toggleVisibility
     * Dans la table parent, appellé comme suit :
    public function toggleVisibility($intId, $blnPublished)
    {
    return TypeChamp::toggleVisibility($this, $intId, $blnPublished);
    }
     *
     * @param  [type] $table
     * @param  [type] $intId
     * @param  [type] $blnPublished
     * @return void
     */
    public function toggleVisibility($table, $intId, $blnPublished)
    {
        $tableName = get_class($table);
        $table->createInitialVersion($tableName, $intId);

        // Trigger the save_callback
        if (is_array($GLOBALS['TL_DCA'][$tableName]['fields']['published']['save_callback'])) {
            foreach ($GLOBALS['TL_DCA'][$tableName]['fields']['published']['save_callback'] as $callback) {
                $table->import($callback[0]);
                $blnPublished = $table->$callback[0]->$callback[1]($blnPublished, $table);
            }
        }

        // Update the database
        $table->Database->prepare("UPDATE " . $tableName . " SET tstamp=" . time() . ", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")->execute($intId);
    }

    /**
     * checkAlias
     * vérifie que l'alias n'éxiste pas déjà. Si c'est le cas il ajoute -1 jusqu'a trouver un alias libre
     *
     * @param  string  $baseStr
     * @param  string  $table
     * @param  integer $i
     * @return string
     */
    public static function checkAlias($baseStr, $table, $i = 0)
    {
        $str = ($i != 0) ? $baseStr . '-' . $i : $baseStr;

        if ($table->Database->prepare("SELECT id FROM " . get_class($table) . " WHERE alias=?")->execute($str)->numRows > 0) {
            return self::checkAlias($baseStr, $table, $i + 1);
        } else {
            return $str;
        }
    }

    /**
     * generateAlias
     * créer un alias a partir des données envoyées dans le tableau $values
     *
     * @param  array $values
     * @param  string $table
     * @return string
     */
    public static function generateAlias($values, $table)
    {
        $varValue = implode('_', $values);
        $alias    = \System::getContainer()->get('contao.slug')->generate($varValue);
        return self::checkAlias($alias, $table);
    }

    /**
     * printIcon
     * dépréciée
     *
     * @param  string $fichier
     * @return string
     */
    public static function printIcon($fichier)
    {
        return sprintf('%simg/%s', $GLOBALS['assetsFolder']['ContaoTypechampBundle'], $fichier);
    }

    /**
     * convertAbsoluteLinks
     *
     * @param  string $strContent
     * @return string
     */
    public function convertAbsoluteLinks($strContent)
    {
        return str_replace('src="' . \Environment::get('base'), 'src="', $strContent);
    }

    /**
     * convertRelativeLinks
     *
     * @param  string $strContent
     * @return string
     */
    public function convertRelativeLinks($strContent)
    {
        return $this->convertRelativeUrls($strContent);
    }

    public function sortUp($table, $row, $href, $label, $title, $icon, $attributes)
    {
        $db = \Database::getInstance();

        //MISE A JOUR DE L'ORDRE
        if (isset($_GET['sortId']) and $_GET['ordre'] == "up") {
            $thisId = $_GET['sortId'];
            $dbObj  = $db->prepare("SELECT * FROM $table ORDER BY ordre ASC")->execute();
            if ($dbObj->numRows == null) {
                return false;
            }

            $allData = $dbObj->fetchAllAssoc();
            foreach ($allData as $key => $data) {
                if ($data['id'] == $thisId) {
                    $temp        = $data;
                    $newPosition = $key - 1;
                    unset($allData[$key]);
                }
            }
            if ($newPosition < 0) {
                $newPosition = 0;
            }

            if ($newPosition > count($allData)) {
                $newPosition = count($allData);
            }

            $newPosition = intval($newPosition);
            $allData     = array_values($allData);
            $newSorting  = array();
            $counter     = 0;
            foreach ($allData as $key => $data) {
                if ($key == $newPosition) {
                    $db->prepare("UPDATE $table SET ordre=? WHERE id=?")->execute($counter, $temp['id']);
                    $counter++;
                }
                $db->prepare("UPDATE $table SET ordre=? WHERE id=?")->execute($counter, $data['id']);
                $counter++;
            }
            \Backend::redirect(\Backend::getReferer());
        }

        //CREATION DES LIGNE DE BOUTONS
        $res    = $db->prepare("SELECT * FROM $table ORDER BY ordre ASC")->execute();
        $nbrows = $res->numRows;
        if ($nbrows > 0) {
            $counter = 0;
            while ($res->next()) {
                if ($res->id == $row['id']) {
                    if ($counter == 0) {
                        return '<span>' . \Backend::generateImage('demagnify.gif', $label) . '</span> ';
                    } else {
                        $href .= '&amp;sortId=' . $row['id'] . '&amp;sortValue=' . $row['ordre'];
                        return '<a href="' . \Backend::addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . \Backend::generateImage($icon, $label) . '</a> ';
                    }
                }
                $counter++;
            }
        }
    }

    public function sortDown($table, $href, $label, $title, $icon, $attributes)
    {
        $db = \Database::getInstance();
        if (isset($_GET['sortId']) and $_GET['ordre'] == "down") {
            $thisId = $_GET['sortId'];
            $dbObj  = $db->prepare("SELECT * FROM  $table ORDER BY ordre ASC")->execute();
            if ($dbObj->numRows == null) {
                return false;
            }

            $allData = $dbObj->fetchAllAssoc();
            foreach ($allData as $key => $data) {
                if ($data['id'] == $thisId) {
                    $temp        = $data;
                    $newPosition = $key + 1;
                    unset($allData[$key]);
                }
            }
            if ($newPosition < 0) {
                $newPosition = 0;
            }

            if ($newPosition > count($allData)) {
                $newPosition = count($allData);
            }

            $newPosition = intval($newPosition);
            $allData     = array_values($allData);
            $newSorting  = array();
            $counter     = 0;
            foreach ($allData as $key => $data) {
                if ($key == $newPosition) {
                    $db->prepare("UPDATE  $table SET ordre=? WHERE id=?")->execute($counter, $temp['id']);
                    $counter++;
                }
                $db->prepare("UPDATE  $table SET ordre=? WHERE id=?")->execute($counter, $data['id']);
                $counter++;
            }
            \Backend::redirect(\Backend::getReferer());
        }
        $dbObj  = $db->prepare("SELECT * FROM  $table ORDER BY ordre ASC")->execute();
        $anzObj = $dbObj->numRows;
        if ($anzObj != null) {
            $counter = 0;
            while ($dbObj->next()) {
                if ($dbObj->id == $row['id']) {
                    if ($counter == $anzObj - 1) {
                        /* Last */
                        return '<span>' . \Backend::generateImage('demagnify.gif', $label) . '</span> ';
                    } else {
                        $href .= '&amp;sortId=' . $row['id'] . '&amp;sortValue=' . $row['ordre'];
                        return '<a href="' . \Backend::addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>' . \Backend::generateImage($icon, $label) . '</a> ';
                    }
                }
                $counter++;
            }
        }
    }
}

class_alias(TypeChamp::class, 'TypeChamp');
