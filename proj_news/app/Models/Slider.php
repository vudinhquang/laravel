<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use DB;

class Slider extends Model
{
    protected $table = 'slider';
    protected $folderUpload = 'slider';
    protected $primaryKey = 'id';
    public $timestamps = false;
    const CREATED_AT = 'created';
    const UPDATED_AT = 'modified';
    protected $fieldsearchAccepted = [
        'id',
        'name',
        'description',
        'link'
    ];

    protected $crudNotAccepted = [
        '_token',
        'thumb_current',
    ];

    public function listItems($params = null, $options = null){
        $result = null;
        if ($options['task'] === 'admin-list-items') {
            $query = $this->select('id', 'name', 'description', 'status', 'link', 'thumb', 'created', 'created_by', 'modified', 'modified_by');    
            if ($params['filter']['status'] !== 'all') {
                $query->where('status', '=', $params['filter']['status']);
            }

            if ($params['search']['value'] !== '') {
                if ($params['search']['field'] === 'all') {
                    $query->where(function($query) use ($params) {
                        foreach ($this->fieldsearchAccepted as $column) {
                            $query->orWhere($column, 'LIKE', "%{$params['search']['value']}%");
                        }
                    });
                } else if (in_array($params['search']['field'], $this->fieldsearchAccepted)) {
                    $query->where($params['search']['field'], 'LIKE', "%{$params['search']['value']}%");
                }
            }
            $result = $query->orderBy('id', 'desc')
                    ->paginate($params['pagination']['totalItemsPerPage']);
        }

        return $result;
    }

    public function countItems($params = null, $options = null){
        $result = null;
        if ($options['task'] === 'admin-count-items-group-by-status') {
            $query = self::select(DB::raw('count(id) as count, status'));
                        
            if ($params['search']['value'] !== '') {
                if ($params['search']['field'] === 'all') {
                    $query->where(function($query) use ($params) {
                        foreach ($this->fieldsearchAccepted as $column) {
                            $query->orWhere($column, 'LIKE', "%{$params['search']['value']}%");
                        }
                    });
                } else if (in_array($params['search']['field'], $this->fieldsearchAccepted)) {
                    $query->where($params['search']['field'], 'LIKE', "%{$params['search']['value']}%");
                }
            }

            $query->groupBy('status');
            $result = $query->get()->toArray();
        }

        return $result;
    }

    public function getItem($params = null, $options = null) {
        $result = null;
        if ($options['task'] === 'get-item') {
            $result = self::select('id', 'name', 'description', 'status', 'link', 'thumb')
                        ->where('id', $params['id'])->first()->toArray();
        }

        if ($options['task'] === 'get-thumb') {
            $result = self::select('id', 'thumb')->where('id', $params['id'])->first()->toArray();
        }

        return $result;
    }

    public function saveItem($params = null, $options = null) {
        if ($options['task'] === 'change-status') {
            $status = ($params['currentStatus'] === 'active') ? 'inactive' : 'active';
            self::where('id', $params['id'])->update(['status' => $status]);
        }

        if ($options['task'] === 'add-item') {
            $params['created_by'] = 'quang';
            $params['created'] = date('Y-m-d');
            $params['thumb'] = $this->_uploadThumb($params['thumb']);
            self::insert($this->_prepareParams($params));
        }

        if ($options['task'] === 'edit-item') {
            if (isset($params['thumb']) && !empty($params['thumb'])) {
                $this->_deleteThumb($params['thumb_current']);
                $params['thumb'] = $this->_uploadThumb($params['thumb']);
            }
            $params['modified_by'] = 'quang';
            $params['modified'] = date('Y-m-d');
            self::where('id', $params['id'])->update($this->_prepareParams($params));
        }
    }

    public function deleteItem($params = null, $options = null) {
        if ($options['task'] === 'delete-item') {
            $item = self::getItem($params, ['task' => 'get-thumb']);
            $this->_deleteThumb($item['thumb']);
            self::where('id', $params['id'])->delete();
        }
    }

    private function _uploadThumb($thumbObj) {
        $thumbName = Str::random(10) . '.' . $thumbObj->clientExtension();
        $thumbObj->storeAs($this->folderUpload, $thumbName, 'zvn_storage_image');

        return $thumbName;
    }

    private function _deleteThumb($thumbName) {
        Storage::disk('zvn_storage_image')->delete($this->folderUpload . '/' . $thumbName);
    }

    private function _prepareParams($params) {
        return $params = array_diff_key($params, array_flip($this->crudNotAccepted));
    }
}
