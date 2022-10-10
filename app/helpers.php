<?php

use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
 * Display notification if there are any
 * @return void
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function displayNotification()
{
    if (session()->has('success')) {
        echo '<div class="alert alert-success d-flex justify-content-center">';
        echo '<span>' . session()->get('success') . '</span>';
        session()->forget('success');
        echo '</div>';
    }
}

/**
 * Print the result from query
 * @return void
 */
function displayTableResult($data, $table, $teams = []): void
{
    $arrData = $data->toArray();
    $arrData = $arrData['data'];
    $colspan = ($table == 'teams') ? 3 : 6;
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
    echo '<a class="btn btn-danger" href="' . setHrefTeam('delete', $record['id']) . '">DELETE</a>';
    echo '</div>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
}

/**
 * Print each row of result
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
    echo '<a class="btn btn-danger" href="' . setHrefEmployee('delete', $record['id']) . '">DELETE</a>';
    echo '</div>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
}

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

function setTeamNameByID($teamID, $teamList)
{
    foreach ($teamList as $team) {
        if ($team->id == $teamID) {
            return $team->name;
        }
    }
}

/**
 * Get button type and target id -> Return a route redirect
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
 * Get button type and target id -> Return a route redirect
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
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function setDropdown(array $data, $dropDownId, $dropDownName)
{
    $request = request()->has($dropDownName) ?? false;
    $old = session()->has('_old_input') ?? false;

    dump($request);
    dump($old);

    $oldSelectionName = '';

    if (!$old && !$request) {
        echo '<select id="' . $dropDownId . '" name="' . $dropDownName . '">';
        echo '<option value="">-Select-</option>';
    }
    elseif ($request){
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
        echo '<option value="'.session()->get('_old_input')[$dropDownName].'">'.$oldSelectionName.'</option>';
    }
    elseif($old) {
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
        dump(old($dropDownName));
        echo '<select id="' . $dropDownId . '" name="' . $dropDownName . '">';
        echo '<option value="' . old($dropDownName) . '">' . $oldSelectionName . '</option>';
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

function isChecked($field, $value)
{
    if (request()->get($field) == $value) {
        return 'checked';
    }
    elseif (old($field) == $value) {
        return 'checked';
    }
}

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

/**
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function displayImage()
{
    return session()->get('tempImgUrl') ?? '/default/avatar/default-user-avatar.png';
}

function displayPassword($password)
{
    return str_repeat('*', strlen($password));
}

function displayTeamName($teamId, $teamsList)
{
    foreach ($teamsList as $key) {
        if ($key->id == $teamId) {
            return $key->name;
        }
    }
}

function displayDropDownInput($id, $listValue)
{
    foreach ($listValue as $key) {
        if ($key['id'] == $id) {
            return $key['name'];
        }
    }
}

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

function replacePercent(string $phrase)
{
    return str_replace('%', '\%', $phrase);
}

/**
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function setDropdownEdit(array $data, $dropDownId, $dropDownName, $target)
{
    $old = request()->has($dropDownName) ?? false;
    $name = getOldName($data, $target[$dropDownName]);

    if (!$old) {
        echo '<select id="' . $dropDownId . '" name="' . $dropDownName . '">';
        echo '<option value="' . $target[$dropDownName] . '">' .$name . '</option>';
    }
    else {
        $oldSelectionName = getOldName($data, request()->get($dropDownName));
        echo '<select id="' . $dropDownId . '" name="' . $dropDownName . '">';
        echo '<option value="' . request()->get($dropDownName) . '">' . $oldSelectionName . '</option>';
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

function isCheckedEdit($field, $value){
    if($field == $value){
        return 'checked';
    }
}

function setDate($date){
    $correctDate = strstr($date, ' ', true);
    if(!$correctDate){
        return $date;
    }
    return $correctDate;
}

function correctingInputForEdit($data){
    if(request()->hasFile('avatar')){
        $data['avatar'] = session('tempImgUrl');
    } else {
        $data['avatar'] = session('avatar_path');
    }
    foreach ($data as $key => $value){
        if ( $value == null || $value = ''){
            unset($data[$key]);
        }
    }
    return $data;
}
