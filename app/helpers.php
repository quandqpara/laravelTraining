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
        if (!is_array($row)) {
            fputcsv($handle, $row->toArray(), ',');
        }
        fputcsv($handle, $row, ',');
    }
    fclose($handle);
}

//------------------------------------------COMMON VIEW HELPERS---------------------------------------------------------
/**
 * Print the result from query
 * @return void
 */
function displayTableResult($data, $table, $teams = []): void
{
    $colspan = ($table == 'teams') ? 3 : 6;

    $arrData = $data->toArray();
    $arrData = $arrData['data'];

    if (empty($data) || empty($arrData)) {
        echo '<tr>';
        echo '<td colspan="' . $colspan . '"><span>No Results Found!</span></td>';
        echo '</tr>';
    } else {
        foreach ($data as $record) {
            if ($table == 'teams') {
                printRow($record);
            } elseif ($table == 'employees') {
                printRowEmployee($record, $teams);
            }
        }
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
        echo '<div class="alert alert-success d-flex justify-content-center">';
        echo '<span>' . session()->get('message') . '</span>';
        session()->forget('message');
        echo '</div>';
    }
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
 * Print each row of result
 * @param $record
 * @return void
 */
function printRow($record)
{
    echo '<tr>';
    foreach ($record->toArray() as $key) {
        echo '<td>' . $key . '</td>';
    }
    echo '<td class="col-2">';
    echo '<div class="btn-container">';
    echo '<div class="col-auto">';
    echo '<a class="btn btn-dark" href="' . setHrefTeam('edit', $record['id']) . '">EDIT</a>';
    echo '</div>';
    echo '<div class="col-auto">';
    echo "<a  class=\"btn btn-danger\"
              href=\"" . setHrefTeam('delete', $record['id']) . "\"
              onclick=\"return confirm('Are you sure to delete this?')\"
            >DELETE</a>";
    echo '</div>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
}

/**
 * SET button type and target id -> Return a route redirect
 * @param $buttonType
 * @param $id
 * @return string|void
 */
function setHrefTeam($buttonType, $id)
{

    if ($buttonType == 'edit') {
        return route('team.editTeam', ['id' => $id]);
    }
    if ($buttonType == 'delete') {
        return route('team.delete', ['id' => $id]);
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
        if ($team->id == $teamID) {
            return $team->name;
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
 * Print each row of result for employee
 * @param $record
 * @return void
 */
function printRowEmployee($record, $teams = [])
{
    $handledRecord = handleEmployeeRecord($record, $teams);
    echo '<tr>';
    foreach ($handledRecord as $key => $value) {
        if ($key == 'avatar') {
            echo '<td><img src="' . asset($value) . '"></td>';
        } else {
            echo '<td>' . $value . '</td>';
        }
    }
    echo '<td class="col-2">';
    echo '<div class="btn-container">';
    echo '<div class="col-auto">';
    echo '<a class="btn btn-dark" href="' . setHrefEmployee('edit', $record['id']) . '">EDIT</a>';
    echo '</div>';
    echo '<div class="col-auto">';
    echo "<a  class=\"btn btn-danger\"
              href=\"" . setHrefEmployee('delete', $record['id']) . "\"
              onclick=\"return confirm('Are you sure to delete this?')\"
            >DELETE</a>";
    echo '</div>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
}

/**
 * Handling data of an employee before printing it out
 * @param $record
 * @param $teams
 * @return array
 */
function handleEmployeeRecord($record, $teams = [])
{
    $handleRecord = [];
    $handleRecord['id'] = $record['id'];
    $handleRecord['avatar'] = $record['avatar'];
    $handleRecord['team_id'] = setTeamNameByID($record['team_id'], $teams);
    $handleRecord['name'] = $record['last_name'] . ' ' . $record['first_name'];
    $handleRecord['email'] = $record['email'];

    return $handleRecord;
}

/**
 * SET button type and target id -> Return a route redirect
 * @param $buttonType
 * @param $id
 * @return string|void
 */
function setHrefEmployee($buttonType, $id)
{
    if ($buttonType == 'edit') {
        return route('employee.editEmployee', ['id' => $id]);
    }
    if ($buttonType == 'delete') {
        return route('employee.delete', ['id' => $id]);
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
        if ($key->id == $teamId) {
            return $key->name;
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
