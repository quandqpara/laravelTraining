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
function displayTableResult($teams): void
{
    $teamData = $teams->toArray();
    $teamData = $teamData['data'];

    if (empty($teams) || empty($teamData)) {
        echo '<tr>';
        echo '<td colspan="3"><span>No Results Found!</span></td>';
        echo '</tr>';
    } else {
        foreach ($teams as $team) {
            printRow($team);
        }
    }
}

/**
 * Print each row of result
 * @param $team
 * @return void
 */
function printRow($team)
{
    echo '<tr>';
    foreach ($team->toArray() as $key) {
        echo '<td>' . $key . '</td>';
    }
    echo '<td class="col-2">';
    echo '<div class="btn-container">';
    echo '<div class="col-auto">';
    echo '<a class="btn btn-dark" href="' . setHrefTeam('edit', $team['id']) . '">EDIT</a>';
    echo '</div>';
    echo '<div class="col-auto">';
    echo '<a class="btn btn-danger" href="' . setHrefTeam('delete', $team['id']) . '">DELETE</a>';
    echo '</div>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
}

/**
 * Get button type and target id -> Return a route redirect
 * @param $buttonType
 * @param $id
 * @return string|void
 */
function setHrefTeam($buttonType, $id)
{
    if ($buttonType = 'edit') {
        return route('team.editTeam', ['id' => $id]);
    }
    if ($buttonType = 'delete') {
        return route('team.delete', ['id' => $id]);
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
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function setDropdown(array $data, $dropDownId, $dropDownName){
    $old = request()->has($dropDownName) ?? false;
    if(!$old){
        echo '<select id="'.$dropDownId. '" name="'.$dropDownName.'">';
        echo '<option value="" disabled selected>-Select-</option>';
    }

    else {

        $oldSelectionName = '';
        foreach($data as $key){
            if(!is_array($key)) {
                if ($key->id == request()->get($dropDownName)) {
                    $oldSelectionName = $key->name;
                }
            } else {
                if ($key['id'] == request()->get($dropDownName)) {
                    $oldSelectionName = $key['name'];
                }
            }

        }

        echo '<select id="'.$dropDownId. '" name="'.$dropDownName.'">';
        echo '<option value="'.request()->get($dropDownName).'">'.$oldSelectionName.'</option>';
    }

    foreach ($data as $key){
        if(!is_array($key)){
            echo '<option value="'.$key->id.'">'.$key->name.'</option>';
        } else {
            echo '<option value="'.$key['id'].'">'.$key['name'].'</option>';
        }
    }
    echo '</select>';
}

function isChecked($field, $value){
    if(request()->get($field) == $value){
        return 'checked';
    }
}

function handleAvatar(){
    $imageName = null;
    $imageUrl = null;

    if(request()->hasFile('avatar')) {
        $image = request()->file(('avatar'));
        $imageName = 'temp_'.time().'_'.$image->getClientOriginalName();
        $image->storeAs('public/temp/', $imageName);
        $imageUrl = 'storage/temp/'.$imageName;
        session()->put('tempImgUrl', $imageUrl);
    } else {
        $imageName = str_replace('storage/temp/','', session()->get('tempImgUrl'));
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
function displayImage(){
    return session()->get('tempImgUrl') ?? '/default/avatar/default-user-avatar.png';
}

function displayPassword($password){
    return str_repeat('*', strlen($password));
}

function displayTeamName($teamId, $teamsList){
    foreach ($teamsList as $key){
        if($key->id == $teamId){
            return $key->name;
        }
    }
}

function displayDropDownInput($id, $listValue){
    foreach ($listValue as $key){
        if($key['id'] == $id){
            return $key['name'];
        }
    }
}

function displayRadioInput($field, $value){
    if($field == 'gender'){
        if($value == 1){
            return 'Male';
        }
        return 'Female';
    } elseif ($field == 'status'){
        if($value == 1){
            return 'On working';
        }
        return 'Retired';
    }
}
