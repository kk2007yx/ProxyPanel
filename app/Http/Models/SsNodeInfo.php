<?php

namespace App\Http\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * SS节点负载情况
 * Class SsNodeInfo
 *
 * @package App\Http\Models
 * @mixin Eloquent
 * @property int    $id
 * @property int    $node_id  节点ID
 * @property int    $uptime   后端存活时长，单位秒
 * @property string $load     负载
 * @property int    $log_time 记录时间
 * @method static Builder|SsNodeInfo newModelQuery()
 * @method static Builder|SsNodeInfo newQuery()
 * @method static Builder|SsNodeInfo query()
 * @method static Builder|SsNodeInfo whereId($value)
 * @method static Builder|SsNodeInfo whereLoad($value)
 * @method static Builder|SsNodeInfo whereLogTime($value)
 * @method static Builder|SsNodeInfo whereNodeId($value)
 * @method static Builder|SsNodeInfo whereUptime($value)
 */
class SsNodeInfo extends Model
{
	public $timestamps = FALSE;
	protected $table = 'ss_node_info';
	protected $primaryKey = 'id';

}