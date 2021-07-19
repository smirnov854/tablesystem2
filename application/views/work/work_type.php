<div id="vue-container">
    <button class="btn btn-primary add_object" ref='add_button' data-toggle="modal" data-target="#add_object_modal">Добавить</button>
    <!--<paginator v-bind:pages="pages"></paginator>-->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Наименование</th>            
        </tr>
        </thead>
        <tbody>

        <tr v-for="(work,index) in work_type">
            <td>{{work.id}}</td>
            <td>{{work.name}}</td>           
            <td>
                <span class="fa fa-pencil edit-object" v-on:click="edit_work_type(work.id,work.name)"></span>
                <span class="fa fa-remove delete-object" v-on:click="delete_work_type(index,work.id)"></span>
            </td>
        </tr>

        </tbody>
    </table>
    <!--<paginator v-bind:pages="pages"></paginator>-->
    <div id="add_object_modal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Добавление типа работы</div>
                </div>
                <div class="modal-body">
                    <input type="hidden" v-model="new_work_type.edit_id">
                    <div class="alert alert-danger" v-if="error">{{error}}</div>
                    <div class="form-group">
                        <input class="form-control" type="text" v-model="new_work_type.name" placeholder="Название" required>
                    </div>                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    <input v-bind:value="new_work_type.edit_id == 0 ? 'Добавить' : 'Редактировать'" class="btn btn-success" id="confirm_add_work_type" v-on:click="add_new_work_type(new_work_type)">
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
            error:"",
            new_work_type:{
                edit_id:0,
                name:'',
            },
            work_type: [
                <?php foreach($list as $row):?>
                {id:<?=$row->id?>, name: '<?=$row->name?>'},
                <?php endforeach;?>
            ]
        },
        methods: {
            add_new_work_type: function (new_work_type) {
                var errors = this.check_form(new_work_type)
                if(errors.length>0){
                    this.error = errors.join(" ")
                    return;
                }
                var url = "/work/add_new_work_type";
                if(this.new_work_type.edit_id !=0 ){
                    url = "/work/add_new_work_type/"+this.new_work_type.edit_id;
                }
                axios.post(url,{
                    name:new_work_type.name,                    
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
            check_form : function(new_work_type){
                var errors = [];
                if(!new_work_type.name){
                    errors.push("Укажите название!");
                }
                return errors;
            },
            edit_work_type : function (id,name){
                this.new_work_type.edit_id=id
                this.new_work_type.name=name                
                this.$refs.add_button.click()
            },
            delete_work_type : function(index,id){
                this._data.work_type.splice(index,1);
                axios.post("/work/set_delete_work_type/"+id,{
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
            count_pages: function(){
                el._data.pages.splice(0);
                for(let z=1;z<=Math.ceil(el._data.total_rows/el._data.per_page);z++){
                    el._data.pages.push(z)
                }
            },
        },        
    })
</script>