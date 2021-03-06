<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="page-header">
  <h2>MySQL 复制监控平台<small> &nbsp;&nbsp;最新检测时间：<?php if(!empty($datalist)){ echo $datalist[0]['create_time'];} ?> (每30秒采集1次数据)</small></h2>
</div>
  


<div class="ui-state-default ui-corner-all" style="height: 45px;" >
<p><span class="ui-icon ui-icon-search" style="float: left; margin-right: .3em;"></span>
                    
<form name="form" class="form-inline" method="get" action="<?php site_url('mysql/replication') ?>" >
  <input type="hidden" name="search" value="submit" />
  <select name="application" class="input-medium" style="">
  <option value="">选择应用</option>
  <?php foreach ($application  as $item):?>
  <option value="<?php echo $item['name'];?>" <?php if($setval['application']==$item['name']) echo "selected"; ?> ><?php echo $item['display_name'] ?>(<?php echo $item['name'] ?>)</option>
   <?php endforeach;?>
  </select>
  <select name="server_id" class="input-medium" style="" >
  <option value="">选择主机</option>
  <?php foreach ($server as $item):?>
  <option value="<?php echo $item['id'];?>" <?php if($setval['server_id']==$item['id']) echo "selected"; ?> ><?php echo $item['host'];?>:<?php echo $item['port'];?></option>
   <?php endforeach;?>
  </select>
  <select name="role" class="input-small" >
  <option value="">角色</option>
  <option value="master" <?php if($setval['role']=='master') echo "selected"; ?> >主库</option>
  <option value="slave" <?php if($setval['role']=='slave') echo "selected"; ?> >备库</option>
  </select>
  <select name="delay" class="input-small" style="width: 110px;">
  <option value="">备库延时</option>
  <option value="30" <?php if($setval['delay']=='30') echo "selected"; ?> >> 30秒</option>
  <option value="60" <?php if($setval['delay']=='60') echo "selected"; ?> >> 1分钟</option>
  <option value="300" <?php if($setval['delay']=='300') echo "selected"; ?> >> 5分钟</option>
  <option value="600" <?php if($setval['delay']=='600') echo "selected"; ?> >> 10分钟</option>
  <option value="1800" <?php if($setval['delay']=='1800') echo "selected"; ?> >> 30分钟</option>
  <option value="3600" <?php if($setval['delay']=='3600') echo "selected"; ?> >> 1小时</option>
  <option value="28800" <?php if($setval['delay']=='28800') echo "selected"; ?> >> 8小时</option>
  <option value="86400" <?php if($setval['delay']=='86400') echo "selected"; ?> >> 1天</option>
  </select>
  <select name="order" class="input-small" style="width: 110px;">
  <option value="id">排序字段</option>
  <option value="id" <?php if($setval['order']=='id') echo "selected"; ?> >默认ID</option>
  <option value="delay" <?php if($setval['order']=='delay') echo "selected"; ?> >延时时间</option>
  </select>
  <select name="order_type" class="input-small" style="width: 110px;">
  <option value="asc" <?php if($setval['order_type']=='asc') echo "selected"; ?> >默认升序</option>
  <option value="desc" <?php if($setval['order_type']=='desc') echo "selected"; ?> >降序排序</option>
  </select>
  <button type="submit" class="btn btn-success">检索</button>
  <a href="<?php echo site_url('mysql/replication') ?>" class="btn btn-warning">重置</a>
  &nbsp;
  <label class="checkbox">自动刷新
    <div class="make-switch" data-on="primary" data-off="danger" data-on-label="ON" data-text-label="">
    <input type="checkbox" name="reflesh" id="reflesh" value="" checked="checked" >
    </div>
  </label>
  <script type="text/javascript">
    function reflesh(){
        //var check_status=$("#reflesh").attr("checked");
        //alert(check_status);
        var arrays = new Array();   //创建一个数组对象
        var items = document.getElementsByName("reflesh");  //获取name为check的一组元素(checkbox)
        for(i=0; i < items.length; i++){  //循环这组数据
	       if(items[i].checked){      //判断是否选中
		    arrays.push(items[i].value);  //把符合条件的 添加到数组中. push()是javascript数组中的方法.
	       }
        }
        //alert( "选中的个数为："+arrays.length  );
        check_count=arrays.length;

        if (check_count==1){ //判断选择框是否选中
                document.location.reload();    
        }
	}
	setInterval("reflesh()",60*1000);//每10秒钟刷新一次 
    </script>

</form>
                    
</div>

                



<table class="table table-hover table-striped  table-bordered table-condensed"  >
	<tr>
		<th colspan="4"><center>服务器</center></th>
		<th colspan="3"><center>复制线程</center></th>
        <th colspan="2"><center>主库信息</center></th>
		<th colspan="2"><center>当前二进制日志</center></th>
		<th colspan="2"><center>主库二进制日志</center></th>

	</tr>
    <tr>
        <th>主机</th>
        <th>角色</th>
        <th>只读</th>
        <th>应用</th>
		<th>IO线程</th>
        <th>SQL线程</th>
        <th>延时</th>
        <th>主机</th>
		<th>端口</th>
		<th>当前文件</th>
		<th>当前位置</th>
        <th>二进制日志</th>
		<th>位置</th>
	</tr>
	
 <?php if(!empty($datalist)) {?>
 <?php foreach ($datalist  as $item):?>
    <tr style="font-size: 12px;">
        <td><?php  echo $item['host'].':'. $item['port'] ?></td>
         <td><?php echo check_role($item['master'],$item['slave']) ?></td>
        <td><?php echo $item['read_only'] ?></td>
        <td><?php echo $item['application'] ?></td>
        <td><?php echo check_value($item['slave_io_run']) ?></td>
        <td><?php echo check_value($item['slave_sql_run']) ?></td>
		<td><?php echo check_delay($item['delay']) ?></td>
        <td><?php echo check_value($item['master_server']) ?></td>
        <td><?php echo check_value($item['master_port']) ?></td>
        <td><?php echo $item['current_binlog_file'] ?></td>
        <td><?php echo $item['current_binlog_pos'] ?></td>
        <td><?php echo $item['master_binlog_file'] ?></td>
        <td><?php echo $item['master_binlog_pos'] ?></td>
       
        
	</tr>
 <?php endforeach;?>
<?php }else{  ?>
<tr>
<td colspan="12">
<font color="red">对不起，没有查询到相关数据！</font>
</td>
</tr>
<?php } ?>
	 
</table>


