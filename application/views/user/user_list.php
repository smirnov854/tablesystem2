<div id="vue-container">
    <button class="btn btn-primary add_users" data-toggle="modal" data-target="#add_user_modal" ref="add_button">Добавить</button>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Почта</th>
            <th>ФИО</th>
            <th>Роль</th>
            <th>Объекты</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>

        <tr v-for="{id,email,fio,role_name,object_cnt,object_ids,role_id} in users">
            <td>{{id}}</td>
            <td>{{email}}</td>
            <td>{{fio}}</td>
            <td>{{role_name}}</td>
            <td>{{object_cnt}}</td>
            <td><button class="btn btn-sm btn-success edit-user" v-on:click="edit_user(id,email,fio,role_name,object_ids,role_id)">Редактировать</button></td>
        </tr>
        </tbody>
    </table>
    <div id="add_user_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Добавление пользователя</div>
                </div>
                <div class="modal-body">
                    <input type="hidden" v-model="new_user.edit_id">
                    <div class="alert alert-danger" v-if="error">{{error}}</div>
                    <div class="form-group">
                        <input class="form-control" type="email" v-model="new_user.email" placeholder="email" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" v-model="new_user.user_name" placeholder="Фамилия Имя Отчество" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="password" v-model="new_user.password" placeholder="Пароль" required>
                    </div>
                    <div class="form-group">
                        <select class="form-control" v-model="new_user.objects" required multiple>
                            <option v-for="{id,name} in objects" :value="id">{{name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" v-model="new_user.role_id" required>
                            <option v-for="{id,name} in roles" :value="id">{{name}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    <button class="btn btn-success" id="confirm_add_user" v-on:click="add_new_user(new_user)">Добавить</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    el = new Vue({
        el: "#vue-container",
        data: {
            error:"",
            new_user:{
                edit_id:'0',email:'',role_id:'',user_name:'',password:'', objects:[]
            },
            //columns: ['id', 'fio', 'email', 'role_id'],
            users: [
                <?php foreach($users as $row):?>
                {id:<?=$row->id?>, 
                    fio: '<?=$row->name?>', 
                    email: '<?=$row->email?>', 
                    role_name: '<?=$row->role_name?>',
                    object_cnt:'<?= !empty($row->object_cnt) ? $row->object_cnt : ""?>',
                    object_ids: '<?= !empty($row->object_ids) ? $row->object_ids : ""?>'},
                <?php endforeach;?>
            ],
            roles: [
                <?php foreach($roles as $row):?>
                {id:<?=$row->id?>, name: '<?=$row->name?>'},
                <?php endforeach;?>
            ],
            objects:[
                <?php foreach($objects as $row):?>
                {id:<?=$row->id?>, name: '<?=$row->name?>'},
                <?php endforeach;?>
            ],            
        },
        methods: {
            add_new_user: function (new_user) {
                var errors = this.check_form(new_user)
                if(errors.length>0){
                    this.error = errors.join(" ")
                    return;
                }
                var url = "/user/add_new_user";
                if(this.new_user.edit_id !=0 ){
                    url = "/user/edit_user/"+this.new_user.edit_id;
                }
                axios.post(url,{
                        user_name:new_user.user_name,
                        role_id:new_user.role_id,
                        email:new_user.email,
                        password:new_user.password,
                        objects:new_user.objects                    
                }).then(function (result) {
                    switch(result.data.status){
                        case 200:
                            location.reload();                            
                            break;
                        case 300:
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })
            },
            check_form : function(new_user){
                var errors = [];
                if(!new_user.user_name){
                    errors.push("Укажите ФИО!");
                }

                if(!new_user.email){
                    errors.push("Укажите email!");
                }               
                return errors;
            },
            edit_user : function (id,email,fio,role_name,object_ids,role_id){
                this.new_user.edit_id=id
                this.new_user.email=email
                this.new_user.role_id=role_id
                this.new_user.user_name=fio
                this.new_user.role_name=role_name 
                if(object_ids.length>0){
                    var object_list = object_ids.split(",");
                    for (var i in object_list){                        
                        this.new_user.objects.push(object_list[i])
                    }                    
                }
                this.$refs.add_button.click()
                
            }
        }
    })
</script>