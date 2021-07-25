<div id="vue-container" class="container-fluid">
    <div class="col-lg-12 col-md-12 col-sm-12 my-3">
        <div class="form-group col-lg-3 col-md-6 col-sm-12 float-left">
            <label class="col-lg-3 col-md-6 col-sm-12 float-left">Роль</label>
            <select class="col-lg-9  col-md-6 col-sm-12 float-left form-control" v-model="role_search" class="form-control">
                <option></option>
                <option v-for="role in roles" :value="role.id">{{role.name}}</option>
            </select>
        </div>
        <div class="form-group col-lg-3 col-md-6 col-sm-12 float-left">
            <label class="col-lg-3 col-md-6 col-sm-12 float-left">Объект</label>
            <select class="col-lg-9 col-md-6 col-sm-12 float-left form-control" v-model="object_search" class="form-control">
                <option v-for="object in objects" :value="object.id">{{object.name}}</option>
            </select>
        </div>
        <div class="form-group col-lg-3 col-md-6 col-sm-12 float-left">
            <label class="col-lg-3 c float-left">ФИО</label>
            <input class="form-control col-lg-9 float-left" type="text" v-model="fio_search">
        </div>
        <button class="btn btn-success float-left" v-on:click="search(0)">Найти</button>
        <button class="btn btn-primary add_users float-right" data-toggle="modal" data-target="#add_user_modal" ref="add_button">Добавить</button>
    </div>   
    <div class="clearfix"></div>
    <paginator v-bind:pages="pages"></paginator>
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

        <tr class="user_row" v-for="(user, index) in users">
            <td>{{user.id}}</td>
            <td>{{user.email}}</td>
            <td>{{user.name}}</td>
            <td>{{user.role_name}}</td>
            <td class="td_object_list" height="50px" v-bind:title="user.object_names_title">{{user.object_names}}</td>
            <td>
                <span class="fa fa-pencil edit-user" v-on:click="edit_user(user.id,user.email,user.name,user.role_name,user.object_ids,user.role_id)"></span>
                <span class="fa fa-remove edit-user float-right" v-on:click="delete_user(index,user.id)"></span>
            </td>
        </tr>
        </tbody>
    </table>
    <paginator v-bind:pages="pages"></paginator>
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
                    <button class="btn btn-danger close_dialog" data-dismiss="modal">Закрыть</button>
                    <button class="btn btn-success" id="confirm_add_user" v-on:click="add_new_user(new_user)">Добавить</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/resources/js/components.js"></script>
<script type="text/javascript">
    el = new Vue({
        el: "#vue-container",
        data: {
            role_search: 0,
            page_number: 0,
            current_page: 1,
            total_rows: <?=$total_rows?>,
            per_page: 25,
            pages:<?=$total_rows >25 ? '[1,2]' : '[]'?>,
            fio_search: '',
            object_search: '',
            error: "",
            new_user: {
                edit_id: '0', email: '', role_id: '', user_name: '', password: '', objects: []
            },
            //columns: ['id', 'fio', 'email', 'role_id'],
            users: [
                <?php foreach($users as $row):?>
                {
                    id: <?=$row->id?>,
                    name: '<?=$row->name?>',
                    email: '<?=$row->email?>',
                    role_id: '<?=$row->role_id?>',
                    role_name: '<?=$row->role_name?>',
                    object_names : '<?=$row->object_names?>',
                    object_names_title : '<?=$row->object_names_title?>',
                    object_cnt: '<?= !empty($row->object_cnt) ? $row->object_cnt : ""?>',
                    object_ids: '<?= !empty($row->object_ids) ? $row->object_ids : ""?>'
                },
                <?php endforeach;?>
            ],
            roles: [
                <?php foreach($roles as $row):?>
                {id: <?=$row->id?>, name: '<?=$row->name?>'},
                <?php endforeach;?>
            ],
            objects: [
                <?php foreach($objects as $row):?>
                {id: <?=$row->id?>, name: '<?=$row->name?>'},
                <?php endforeach;?>
            ],
        },
        methods: {
            add_new_user: function (new_user) {
                var errors = this.check_form(new_user)
                if (errors.length > 0) {
                    this.error = errors.join(" ")
                    return;
                }
                var url = "/user/add_new_user";
                if (this.new_user.edit_id != 0) {
                    url = "/user/edit_user/" + this.new_user.edit_id;
                }
                
                axios.post(url, {
                    user_name: new_user.user_name,
                    role_id: new_user.role_id,
                    email: new_user.email,
                    password: new_user.password,
                    objects: new_user.objects
                }).then(function (result) {
                    switch (result.data.status) {
                        case 200:
                            alert("Успешно добавлено!");
                            document.querySelector(".close_dialog").click();
                            el.search(1);
                            break;
                        case 300:
                            alert(result.data.message)
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })
            },
            check_form: function (new_user) {
                var errors = [];
                if (!new_user.user_name) {
                    errors.push("Укажите ФИО!");
                }

                if (!new_user.email) {
                    errors.push("Укажите email!");
                }
                return errors;
            },
            edit_user: function (id, email, fio, role_name, object_ids, role_id) {
                this.new_user.edit_id = id
                this.new_user.email = email
                this.new_user.role_id = role_id
                this.new_user.user_name = fio
                this.new_user.role_name = role_name                
                if (object_ids.length > 0) {
                    var object_list = object_ids.split(",");
                    for (var i in object_list) {
                        this.new_user.objects.push(object_list[i])
                    }
                }
                this.$refs.add_button.click()
            },
            delete_user: function (index, id) {
                axios.post("/user/set_delete/" + id, {
                    id: id,
                }).then(function (result) {
                    switch (result.data.status) {
                        case 200:
                            el._data.users.splice(index, 1)
                            break;
                        case 300:
                            alert(result.message)
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })
            },
            search: function (page) {                
                axios.post("/user/search/"+page, {
                    role: this._data.role_search,
                    object_id: this._data.object_search,
                    fio: this._data.fio_search,
                }).then(function (result) {
                    switch (result.data.status) {
                        case 200:
                            el._data.users.splice()
                            el._data.users = result.data.content;
                            el._data.total_rows = result.data.total_rows;
                            el._data.pages.splice(0);
                            
                                for(let z=1;z<=Math.ceil(el._data.total_rows/el._data.per_page);z++){
                                    el._data.pages.push(z)
                                }
                            
                            break;
                        case 300:
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })                
            },
        },       
        
    })
</script>