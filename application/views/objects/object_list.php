<div id="vue-container">
    <button class="btn btn-primary add_users" data-toggle="modal" data-target="#add_user_modal">Добавить</button>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Наименование</th>
            <th>Адрес</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <tr v-for="{id,name,address} in objects">
            <td>{{id}}</td>
            <td>{{name}}</td>
            <td>{{address}}</td>
            <td></td>
        </tr>

        </tbody>
    </table>
    <div id="add_user_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Добавление объекта</div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="error">{{error}}</div>
                    <div class="form-group">
                        <input class="form-control" type="text" v-model="new_object.name" placeholder="Название" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" v-model="new_object.address" placeholder="Адрес" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    <button class="btn btn-success" id="confirm_add_user" v-on:click="add_new_object(new_object)">Добавить</button>
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
            new_object:{
                address:'',name:''
            },
            //columns: ['id', 'fio', 'email', 'role_id'],
            objects: [
                <?php foreach($objects as $row):?>
                {id:<?=$row->id?>, name: '<?=$row->name?>', address: '<?=$row->address?>'},
                <?php endforeach;?>
            ]
        },
        methods: {
            add_new_object: function (new_object) {
                var errors = this.check_form(new_object)
                if(errors.length>0){
                    this.error = errors.join(" ")
                    return;
                }
                axios.post("/object/add_new_object",{
                        name:new_object.name,
                        address:new_object.address
                }).then(function (result) {
                    console.log(result)
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
            check_form : function(new_object){
                var errors = [];
                if(!new_object.name){
                    errors.push("Укажите название!");
                }

                if(!new_object.address){
                    errors.push("Укажите адрес");
                }
                console.log(errors)
                return errors;
            }
        }
    })
</script>