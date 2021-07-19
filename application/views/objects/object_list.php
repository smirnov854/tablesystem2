<div id="vue-container">
    <button class="btn btn-primary add_object" ref='add_button' data-toggle="modal" data-target="#add_object_modal">Добавить</button>
    <paginator v-bind:pages="pages"></paginator>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Наименование</th>
            <th>Адрес</th>
            <th>Описание</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <tr v-for="(object,index) in objects">
            <td>{{object.id}}</td>
            <td>{{object.name}}</td>
            <td>{{object.address}}</td>
            <td>{{object.description}}</td>
            <td>
                <span class="fa fa-pencil edit-object" v-on:click="edit_object(object.id,object.name,object.address,object.description)"></span>
                <span class="fa fa-remove delete-object" v-on:click="delete_object(index,object.id)"></span>
            </td>
        </tr>

        </tbody>
    </table>
    <paginator v-bind:pages="pages"></paginator>
    <div id="add_object_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Добавление объекта</div>
                </div>
                <div class="modal-body">
                    <input type="hidden" v-model="new_object.edit_id">
                    <div class="alert alert-danger" v-if="error">{{error}}</div>
                    <div class="form-group">
                        <input class="form-control" type="text" v-model="new_object.name" placeholder="Название" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" v-model="new_object.address" placeholder="Адрес" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="text" v-model="new_object.description" placeholder="Описание" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    <input v-bind:value="new_object.edit_id == 0 ? 'Добавить' : 'Редактировать'" class="btn btn-success" id="confirm_add_user" v-on:click="add_new_object(new_object)">
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
            page_number: 0,
            current_page: 1,
            total_rows: <?=$total_rows?>,
            per_page: 25,
            pages:[],
            error:"",
            new_object:{
                edit_id:0,
                address:'',
                name:'',
                description:''
            },
            //columns: ['id', 'fio', 'email', 'role_id'],
            objects: [
                <?php foreach($objects as $row):?>
                {id:<?=$row->id?>, name: '<?=$row->name?>', address: '<?=$row->address?>', description :'<?=$row->description?>'},
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
                var url = "/objects/add_new_object";
                if(this.new_object.edit_id !=0 ){
                    url = "/objects/add_new_object/"+this.new_object.edit_id;
                }
                axios.post(url,{
                        name:new_object.name,
                        address:new_object.address,
                    description:new_object.description
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
            },
            edit_object : function (id,name,address,description){
                this.new_object.edit_id=id
                this.new_object.name=name
                this.new_object.address=address
                this.new_object.description=description
                this.$refs.add_button.click()
            },
            delete_object : function(index,id){
                this._data.objects.splice(index,1);
                axios.post("/objects/set_delete/"+id,{
                    id:id,
                }).then(function (result) {
                    switch(result.data.status){
                        case 200:
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
                console.log(page);
                axios.post("/objects/search/"+page, {
                    role: this._data.role_search,
                    object_id: this._data.object_search,
                    fio: this._data.fio_search,
                }).then(function (result) {
                    switch (result.data.status) {
                        case 200:
                            el._data.objects.splice()
                            el._data.objects = result.data.content;
                            el._data.total_rows = result.data.total_rows;
                            el.count_pages()                            
                            break;
                        case 300:
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })
            },
            count_pages: function(){
                el._data.pages.splice(0);
                for(let z=1;z<=Math.ceil(el._data.total_rows/el._data.per_page);z++){
                    el._data.pages.push(z)
                }
            },            
        },
        beforeMount: function(){
            this._data.pages.splice(0);
            for(let z=1;z<=Math.ceil(this._data.total_rows/this._data.per_page);z++){
                this._data.pages.push(z)
            }
        }        
    })
</script>