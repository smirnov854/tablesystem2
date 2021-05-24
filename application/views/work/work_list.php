<!-- Date-picker itself -->
<script src="https://cdn.jsdelivr.net/npm/pc-bootstrap4-datetimepicker@4.17/build/js/bootstrap-datetimepicker.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/pc-bootstrap4-datetimepicker@4.17/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/vue-bootstrap-datetimepicker@5"></script>


<script>
    // Initialize as global component
    Vue.component('date-picker', VueBootstrapDatetimePicker);
    $.extend(true, $.fn.datetimepicker.defaults, {
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'fas fa-calendar-check',
            clear: 'far fa-trash-alt',
            close: 'far fa-times-circle'
        }
    });
</script>


<div id="request_controller" class="justify-content-center mx-4 my-4">
    <div>
        <div class="form-group col-lg-4 float-left">
            <label for="objects" class="col-lg-3 float-left">Объекты</label>
            <select class="form-control float-left col-lg-9" v-model="search_object_id" multiple style="height:200px !important">
                <option v-for="{id,name} in objects" :value="id">{{name}}</option>
            </select>
        </div>
        <div class="form-group col-lg-4 float-left">
            <label for="date_start" class="col-lg-3 float-left">Дата от</label>
            <date-picker v-model='date_from' :config='options' ></date-picker>
            <div class="clearfix"></div>
            <label for="date_end" class="col-lg-3 float-left">Дата по</label>
            <input class="form-control col-lg-6 float-left" type="text" v-model='date_to'>
        </div>
        <div class="form-group col-lg-4 float-left">
            <button class="btn btn-success" v-on:click="search">Найти</button>
            <button class="btn btn-primary add_job" data-toggle="modal" data-target="#add_job">Добавить</button>
        </div>

    </div>    

    <div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Объект</th>
                <th>Добавлена</th>
                <th>Описание</th>
                <th>Работы</th>
                <th>Cдал</th>
                <th>Проверил</th>
                <th>Принял</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="{id,date_add,description,object_name} in requests">
                <td>{{id}}</td>
                <td>{{object_name}}</td>
                <td>{{date_add}}</td>
                <td>{{description}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>

        </table>
    </div>
    

    <div id="add_job" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">Добавление заявки</div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" v-if="error">{{error}}</div>
                    <div class="form-group">
                        <select class="form-control" v-model="new_job.object_id" required>
                            <option v-for="{id,name} in objects" :value="id">{{name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select class="form-control" v-model="new_job.type_id" required>
                            <option v-for="{id,name} in type" :value="id">{{name}}</option>
                        </select>
                    </div>
                    <textarea v-model="new_job.description" class="form-control"></textarea>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    <button class="btn btn-success" id="confirm_add_user" v-on:click="add_new_job(new_job)">Добавить</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    el = new Vue({
        el: "#request_controller",
        data: {
            user_role_id: <?=$role_id?>,
            date_from: '',
            date_to: '',
            search_object_id: [],
            error: "",
            new_job: {
                type_list: '',
                description: ''
            },
            jobs_list: [
                {id: ''}
            ],
            type: [
                <?php foreach($type as $row):?>
                {id: <?=$row->id?>, name: '<?=$row->name?>'},
                <?php endforeach;?>
            ],
            objects: [
                <?php foreach($objects as $row):?>
                {id: <?=$row->id?>, name: '<?=$row->name?>'},
                <?php endforeach;?>
            ],
            requests: [
                <?php foreach($req_list as $row):?>
                {
                    id: <?=$row->id?>,
                    description: '<?=$row->description?>',
                    date_add: '<?=$row->date_add?>',
                    object_name: '<?=$row->object_name?>'
                },
                <?php endforeach;?>
            ]
        },
        methods: {
            add_new_job: function (new_job) {
                var errors = this.check_form(new_job)
                if (errors.length > 0) {
                    this.error = errors.join(" ")
                    return;
                }
                axios.post("/work/add_new_job", {
                    type: new_job.type_id,
                    description: new_job.description,
                    object_id: new_job.object_id
                }).then(function (result) {
                    switch (result.data.status) {
                        case 200:
                            break;
                        case 300:
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })
            },
            check_form: function (new_job) {
                var errors = [];
                if (!new_job.type_id) {
                    errors.push("Укажите тип!");
                }
                if (!new_job.object_id) {
                    errors.push("Укажите объект!");
                }
                if (!new_job.description) {
                    errors.push("Укажите описание!");
                }
                return errors;
            },
            search: function () {

                axios.post("/work/search_req", {
                    objects_id: this.search_object_id,
                    date_from: this.date_from,
                    date_to: this.date_to,
                }).then(function (result) {
                    switch (result.data.status) {
                        case 200:                            
                            el._data.requests.splice(0,el._data.requests.length+1)                            
                            for (var z in result.data.content) {
                                var newReq = {
                                    id: result.data.content[z].id,
                                    object_name: result.data.content[z].object_name,
                                    date_add: result.data.content[z].date_add,
                                    description: result.data.content[z].description
                                }
                                el._data.requests.push(newReq);
                            }
                            break;
                        case 300:
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })
            }
        }
    })
</script>