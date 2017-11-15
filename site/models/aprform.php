<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HelloWorld Model
 *
 * @since  0.0.1
 */
class aprformModelaprform extends JModelLegacy
{
    
    public function loadAprList($id){

        $db = JFactory::getDbo();
        
        $query = $db->getQuery(true);

        $query->select(array('a.id_apr','b.Nombre_unidad'));
        $query->from($db->quoteName('#__ges_APR_usuario','a'));
        $query->join('LEFT',$db->quoteName('#__ges_Unidad_APR','b').' ON ('.$db->quoteName('a.id_apr').'='.$db->quoteName('b.ID_Interno').')');
        $query->where($db->quoteName('a.id_user').'='.$db->quote($id));
        $db->setQuery($query);
        $row = $db->loadRowList();

        return $row;
    }

	public function loadForm($year, $month ,$apr)
    {
            $db = JFactory::getDbo();

            $query = $db->getQuery(true);

            $query->select(array('DAY(fecha)','precipitacion_incidente','calidad','cantidad'));

            $query->from('#__ges_Oferta');

            $query->where('YEAR(fecha)'."=".$db->quote($year))
            ->where('MONTH(fecha)'."=".$db->quote($month))
            ->where('id_unidad ='.($db->quote($apr)));
            $db->setQuery($query);

            $row = $db->loadRowList();
            
            return $row;
    }
    public function saveForm ($year, $month, $id_unidad, $data)
    {
        $db = JFactory::getDbo();

        foreach ($data as $datas)
        {
            $query = $db->getQuery(true);
            {
                $query->select($db->quoteName('id'))
                ->from($db->quoteName('#__ges_Oferta'))
                ->where('fecha'."=".$db->quote($datas["fecha"]))
                ->where('id_unidad'."=".$db->quote($id_unidad));
                $db->setQuery($query);
                $result = $db->loadRow();
                if ($result !=null)
                {
                    $query->clear();
                    $fields = array(
                        'fecha'."=".$db->quote($datas["fecha"]),
                        'precipitacion_incidente'."=".$datas["precipitacion"],
                        'calidad'."=".$datas["calidad"],
                        'cantidad'."=".$datas["cantidad"],
                        'id_unidad'."=".$id_unidad);
                    $query->update($db->quoteName('#__ges_Oferta'))->set($fields);
                    $query->where('fecha'.'='.$db->quote($datas["fecha"]))
                    ->where('id_unidad'."=".$db->quote($id_unidad));
                    $db->setQuery($query);
                    $db->execute();
                }
                else
                {
                    $query->clear();
                    $fields = array('fecha,precipitacion_incidente,calidad,cantidad,id_unidad');
                    $values = array($db->quote($datas[fecha]),$datas[precipitacion],$datas[calidad],$datas[cantidad],$id_unidad);
                    $query->insert($db->quoteName('#__ges_Oferta'))->columns($fields);
                    $query->values(implode(',',$values));
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }
    }
}