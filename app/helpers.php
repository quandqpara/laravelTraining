<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

//------------------------------------------COMMON HELPERS--------------------------------------------------------------
function handleExceptionMessage($error)
{
    $e = "Error: " . $error->getMessage();
    Session::flash('message', $e);
    writeLog($e);
    return $e;
}

function writeLog($log)
{
    $logFile = fopen("log.txt", "a") or die("Unable to open file");
    $log .= "   Time: " . date('d-m-Y H:i:s') . "\n";
    fwrite($logFile, $log);
    fclose($logFile);
}

function exportCSV($lastQueryData)
{
    $handle = fopen('export.csv', 'w') or die("Unable to open file");

    foreach ($lastQueryData as $row) {
        $insertToCSV = [];
        if (!is_array($row)) {
            $row = $row->toArray();
        }

        foreach ($row as $column => $value) {
            array_push($insertToCSV, $column);
            array_push($insertToCSV, $value);
        }

        fputcsv($handle, $insertToCSV, ',');
    }
    fclose($handle);
}

//------------------------------------------COMMON VIEW HELPERS---------------------------------------------------------
function showSortingArrow($currentColumn, $columnOnRequest, $directionOnRequest, $data){
    $arrData = $data->toArray();
    $arrData = $arrData['data'];

    if(empty($arrData)){
        return '';
    }
    if($currentColumn !== $columnOnRequest){
        return '';
    }
    if($directionOnRequest == 'asc'){
        echo '<i class="fa fa-caret-up"></i>';
    }
    else {
        echo '<i class="fa fa-caret-down"></i>';
    }
}

/**
 * Display notification if there are any
 * @return void
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function displayNotification()
{
    if (session()->has('message')) {
        echo '<div class="alert alert-success d-flex justify-content-center notice-message">';
        echo '<span>' . session()->get('message') . '</span>';
        session()->forget('message');
        echo '</div>';
    }
}

/**
 * get Current Page Title
 * @param $url
 * @return mixed|null
 */
function getTitle() {
    $url = $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $page = file_get_contents($url);
    return preg_match('/<title[^>]*>(.*?)<\/title>/ims', $page, $match) ? $match[1] : null;
}

//------------------------------------------VIEW TEAMS------------------------------------------------------------------
/**
 * Set value to input if needed
 * @param $field
 * @return void
 */
function setValue($field)
{
    if (session()->has('success') || empty(request()->all())) {
        echo 'value=""';
    } else {
        echo 'value="' . old($field) . '"';
    }
}

/**
 * SET the team name that is corresponding to the team_ID given
 * @param $teamID
 * @param $teamList
 * @return mixed|void
 */
function setTeamNameByID($teamID, $teamList)
{
    foreach ($teamList as $team) {
        if ($team['id'] == $teamID) {
            return $team['name'];
        }
    }
}

/**
 * @param $selectedColumn -> the column that is about to be clicked
 * @param $previousColumn -> the column on URI a.k.a the previous queries Column
 * @param $previousDirection -< the direction on URI a.k.a the previous queries Direction
 * @return string|void
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function setSortHrefTeam($selectedColumn, $previousColumn, $previousDirection)
{
    //requested column & direction a.k.a previous request
    $column = $previousColumn ?? 'id';
    $direction = $previousDirection ?? 'asc';

    //other stuff
    $page = request()->has('page') ? request()->get('page') : 1;
    $name = request()->has('name') ? request()->get('name') : '';

    //compare previous request column with selected column
    //if they are not the same then direction to start with is asc.
    if ($selectedColumn !== $column) {
        return route('team.search', ['name' => $name, 'page' => $page, 'column' => $selectedColumn, 'direction' => 'asc']);
    } //else if previous direction is 'asc' then set the direction to query to 'desc', vice versa
    else {
        if ($direction == 'asc') {
            $direction = 'desc';
            return route('team.search', ['name' => $name, 'page' => $page, 'column' => $column, 'direction' => $direction]);
        } elseif ($direction == 'desc') {
            $direction = 'asc';
            return route('team.search', ['name' => $name, 'page' => $page, 'column' => $column, 'direction' => $direction]);
        }
    }
}

//-----------------------------------------------EMPLOYEE---------------------------------------------------------------
//-------------------------------------HANDLING FILE
/**
 * HANDLE avatar uploaded!
 * Store img to temp url -> return temp url and auth url
 * @return void
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function handleAvatar()
{
    $imageName = null;
    $imageUrl = null;

    if (request()->hasFile('avatar')) {
        $image = request()->file(('avatar'));
        $imageName = 'temp_' . time() . '_' . $image->getClientOriginalName();
        $image->storeAs('public/temp/', $imageName);
        $imageUrl = 'storage/temp/' . $imageName;
        session()->put('tempImgUrl', $imageUrl);
    } else {
        $imageName = str_replace('storage/temp/', '', session()->get('tempImgUrl'));
        $imageUrl = session()->get('tempImgUrl');
    }

    request()->merge([
        'avatar_name' => $imageName,
        'avatar_url' => $imageUrl,
    ]);

}

//-------------------------------------VIEWS HELPERS
//---------------------------------SEARCH

/**
 * @param $selectedColumn -> the column that is about to be clicked
 * @param $previousColumn -> the column on URI a.k.a the previous queries Column
 * @param $previousDirection -< the direction on URI a.k.a the previous queries Direction
 * @return string|void
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function setSortHrefEmployee($selectedColumn, $previousColumn, $previousDirection)
{
    //requested column & direction a.k.a previous request
    $column = $previousColumn ?? 'id';
    $direction = $previousDirection ?? 'asc';

    //other stuff
    $page = request()->has('page') ? request()->get('page') : 1;
    $name = request()->has('name') ? request()->get('name') : '';
    $team_id = request()->has('team_id') ? request()->get('team_id') : '';
    $email = request()->has('email') ? request()->get('email') : '';

    //compare previous request column with selected column
    //if they are not the same then direction to start with is asc.
    if ($selectedColumn !== $column) {
        return route('employee.search', ['name' => $name, 'team_id' => $team_id, 'email' => $email, 'page' => $page, 'column' => $selectedColumn, 'direction' => 'asc']);
    } //else if previous direction is 'asc' then set the direction to query to 'desc', vice versa
    else {
        if ($direction == 'asc') {
            $direction = 'desc';
            return route('employee.search', ['name' => $name, 'team_id' => $team_id, 'email' => $email, 'page' => $page, 'column' => $column, 'direction' => $direction]);
        } elseif ($direction == 'desc') {
            $direction = 'asc';
            return route('employee.search', ['name' => $name, 'team_id' => $team_id, 'email' => $email, 'page' => $page, 'column' => $column, 'direction' => $direction]);
        }
    }
}

//---------------------------------CREATE/EDIT - CONFIRM VIEWS
/**
 * SET the dropdown input field
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function setDropdown(array $data, $dropDownId, $dropDownName, $target = [])
{
    $request = request()->has($dropDownName) ?? false;
    $old = session()->has('_old_input') ?? false;

    $oldSelectionName = '';

    if (!$old && !$request && empty($target)) {
        echo '<select id="' . $dropDownId . '" name="' . $dropDownName . '">';
        echo '<option value="">-Select-</option>';
    } elseif ($request && empty($target)) {
        foreach ($data as $key) {
            if (!is_array($key)) {
                if ($key->id == request()->get($dropDownName)) {
                    $oldSelectionName = $key->name;
                }
            } else {
                if ($key['id'] == request()->get($dropDownName)) {
                    $oldSelectionName = $key['name'];
                }
            }
        }

        echo '<select id="' . $dropDownId . '" name="' . $dropDownName . '">';
        echo '<option value="' . session()->get('_old_input')[$dropDownName] . '">' . $oldSelectionName . '</option>';
        echo '<option value="">-Empty-</option>';
    } elseif ($old && empty($target)) {
        foreach ($data as $key) {
            if (!is_array($key)) {
                if ($key->id == old($dropDownName)) {
                    $oldSelectionName = $key->name;
                }
            } else {
                if ($key['id'] == old($dropDownName)) {
                    $oldSelectionName = $key['name'];
                }
            }

        }
        echo '<select id="' . $dropDownId . '" name="' . $dropDownName . '">';
        echo '<option value="' . old($dropDownName) . '">' . $oldSelectionName . '</option>';
        echo '<option value="">-Empty-</option>';
    }
    elseif (!empty($target)) {
        foreach ($data as $key) {
            if (!is_array($key)) {
                if ($key->id == $target[$dropDownName]) {
                    $oldSelectionName = $key->name;
                }
            } else {
                if ($key['id'] == $target[$dropDownName]) {
                    $oldSelectionName = $key['name'];
                }
            }
        }
        echo '<select id="' . $dropDownId . '" name="' . $dropDownName . '">';
        echo '<option value="' . $target[$dropDownName] . '">' . $oldSelectionName . '</option>';
        echo '<option value="">-Empty-</option>';
    }

    foreach ($data as $key) {
        if (!is_array($key)) {
            echo '<option value="' . $key->id . '">' . $key->name . '</option>';
        } else {
            echo '<option value="' . $key['id'] . '">' . $key['name'] . '</option>';
        }
    }
    echo '</select>';
}

/** return the last selected value of the dropdown input
 * @param $id
 * @param $listValue
 * @return mixed|void
 */
function displayDropDownInput($id, $listValue)
{
    foreach ($listValue as $key) {
        if ($key['id'] == $id) {
            return $key['name'];
        }
    }
}

/**
 * @param $data
 * @param $id
 * @return mixed|void
 */
function getOldName($data, $id)
{
    $oldSelectionName = '';
    foreach ($data as $key) {
        if (!is_array($key)) {
            if ($key->id == $id) {
                $oldSelectionName = $key->name;
                return $oldSelectionName;
            }
        } else {
            if ($key['id'] == $id) {
                $oldSelectionName = $key['name'];
                return $oldSelectionName;
            }
        }
    }
}

/** LOAD Radio button selection
 * @param $field
 * @param $value
 * @return string|void
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function isChecked($field, $value, $target=[])
{
    if (request()->get($field) == $value) {
        return 'checked';
    } elseif (old($field) == $value) {
        return 'checked';
    } elseif (!empty($target) && $target[$field] == $value){
        return 'checked';
    }
}

/**
 * @param $field
 * @param $value
 * @return string|void
 */
function isCheckedEdit($field, $value)
{
    if (old($field) == $value) {
        return 'checked';
    } elseif ($field == $value) {
        return 'checked';
    }
}

/**
 * Return tempImgURL to display Image Everywhere when having tempUrl for image
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function displayImage()
{
    return session()->get('tempImgUrl') ?? '/default/avatar/default-user-avatar.png';
}

/**
 * Not display password
 * @param $password
 * @return string
 */
function displayPassword($password)
{
    return str_repeat('*', strlen($password));
}

/**
 * @param $teamId
 * @param $teamsList
 * @return mixed|void
 */
function displayTeamName($teamId, $teamsList)
{
    foreach ($teamsList as $key) {
        if ($key['id'] == $teamId) {
            return $key['name'];
        }
    }
}

/**
 * @param $field
 * @param $value
 * @return string|void
 */
function displayRadioInput($field, $value)
{
    if ($field == 'gender') {
        if ($value == 1) {
            return 'Male';
        }
        return 'Female';
    } elseif ($field == 'status') {
        if ($value == 1) {
            return 'On working';
        }
        return 'Retired';
    }
}

/**
 * @param $date
 * @return mixed|string
 */
function setDate($date)
{
    $correctDate = strstr($date, ' ', true);
    if (!$correctDate) {
        return $date;
    }
    return $correctDate;
}

/**
 * @param $data
 * @return mixed
 */
function correctingInputForEdit($data)
{
    if (request()->hasFile('avatar')) {
        $data['avatar'] = session('tempImgUrl');
    } else {
        $data['avatar'] = session('avatar_path');
    }
    foreach ($data as $key => $value) {
        if ($value == null || $value = '') {
            unset($data[$key]);
        }
    }
    return $data;
}

//-------------------------------------------------DB HELPERS
/**
 * Avoiding return all records when input % to search input
 * @param $phrase
 * @return array|string|string[]
 */
function replacePercent($phrase)
{
    return str_contains($phrase, '%') ? str_replace('%', '\%', $phrase) : $phrase;
}
