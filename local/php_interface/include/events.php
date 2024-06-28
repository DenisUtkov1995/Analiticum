<?
//Задание №3
const PRODUCT_IBLOCK = 2;
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "OnBeforeIBlockElementUpdateHandler");
function OnBeforeIBlockElementUpdateHandler(&$arFields){
    if($arFields['IBLOCK_ID']==PRODUCT_IBLOCK && $arFields['ACTIVE']=='N'){
        $res = CIBlockElement::GetList(
            false,
            array('IBLOCK_ID' => PRODUCT_IBLOCK, 'ACTIVE' => 'Y', 'ID' => $arFields['ID'], 'SHOW_COUNTER' > 0),
            false,
            array('nTopCount' => 1),
            array('ID', 'SHOW_COUNTER')
        );
    if($fields = $res->Fetch()){
        global $APPLICATION;
        $APPLICATION->throwException(str_replace('#COUNT#', $fields['SHOW_COUNTER'], GetMessage('ERROR_DEACT')));
        return false;
    }
    }

}

//Задание №2
class UpdateUser
{
    function UpdateUserManager(&$arFields)
    {
        $userContents = false;
        foreach ($arFields['GROUP_ID'] as $group) {
            if ($group['GROUP_ID'] == 5) {
                $userContents = true;
            }
        }

        if ($userContents == true) {

            require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

            $arUserSelect = array(
                'ACTIVE' => 'Y',
                'GROUPS_ID' => array('5')
            );

            $userEmail = array();
            $rsUsers = CUser::GetList(($by = 'id'), ($order = 'asc'), $arUserSelect);
            while ($arUser = $rsUsers->Fetch()):
                $userEmai[] = $arUser['EMAIL'];
            endwhile;

            $arEventFields = array(
                'EMAIL_TO' => implode(', ', $userEmai),
                'NAME' => $arFields['NAME'],
                'LOGIN' => $arFields['LOGIN']
            );

            CEvent::Send('USER_CONTENT_MANAGER_Y', 's1', $arEventFields);
        }
    }
}


?>