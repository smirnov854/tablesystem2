<!-- Date-picker itself -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js" type="text/javascript"></script>

<script src="https://cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js"></script>

<script defer src="https://use.fontawesome.com/releases/v5.0.13/js/all.js"></script>
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/fontawesome.css" integrity="sha384-eHoocPgXsiuZh+Yy6+7DsKAerLXyJmu2Hadh4QYyt+8v86geixVYwFqUvMU8X90l" crossorigin="anonymous"/>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.3"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.22"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4/dist/css/bootstrap.min.css" rel="stylesheet">


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
<style>
    .img_container img {
        max-height: 30px;
        max-width: 30px;
        float: left;
    }

    .block {
        max-height: 130px !important;
        min-height: 50px;
        overflow: auto;
        margin-top: 10px;
    }
</style>


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
            <date-picker class="float-left datepicker col-lg-9" v-model='date_from' :config='options'></date-picker>
            <div class="clearfix"></div>
            <label for="date_end" class="col-lg-3 float-left">Дата по</label>
            <date-picker class="form-control float-left datepicker my-1 col-lg-9" v-model='date_to' :config='options'></date-picker>
            <div class="clearfix"></div>
            <label>Все <input type="radio" id="all_btn" v-model="current_status" value="all"></label>
            <br/>
            <label>В работе <input type="radio" id="in_work" v-model="current_status" value="in_work"></label>
            <br/>
            <label> Выполненные <input type="radio" id="done" v-model="current_status" value="done"></label>
        </div>        
        <div class="form-group col-lg-4 float-left">
            <button class="btn btn-success search_button" v-on:click="search(0)">Найти</button>
            <button class="btn btn-primary add_job" data-toggle="modal" data-target="#add_job">Добавить</button>
        </div>
    </div>

    <div>
        <paginator v-bind:pages="pages"></paginator>

        <div class="border border-dark rounded mx-3 my-3 px-2 py-2" v-for="(request,index) in requests" style="display: table; width:100%">
            <div class="block col-lg-4 col-md-6 col-sm-6 float-left">
                <div class="col-lg-1">ID:{{request.id}}</div>
                <div class="col-lg-11">Наименование объекта:{{request.object_name}}</div>
            </div>
            <div class="block col-lg-3 col-md-6 col-sm-6 float-left">
                <div>Добавлена <span class="float-right">{{request.add_user_name}} {{request.date_add}}</span></div>
                <div v-if="request.user_done_date">Выполнил <span class="float-right">{{request.user_done_date}} {{request.user_done_date}}</span></div>
                <div v-if="request.user_check_date">Проверил <span class="float-right">{{request.user_check}} {{request.user_check_date}}</span></div>
                <div v-if="request.common_check_user">Принял <span class="float-right">{{request.common_check_user}} {{request.common_date}}</span></div>
            </div>
            <div class="block col-lg-5 col-md-6 col-sm-12 float-left">
                <div class="class col-lg-12">Описание:{{request.description}}</div>
            </div>
            <div class="block col-lg-4 col-md-4 col-sm-6 float-left">
                <div class="col-lg-12">Работы</div>
                <div class="col-lg-12">
                    <textarea v-if="user_role_id==4" class="form-control" v-model="request.done_work"></textarea>
                    <button class="btn btn-success btn-sm" v-if="user_role_id==4" v-on:click="save_cur_comment(request.id,index)"><i class="fa fa-check"></i></button>
                    <button class="btn btn-danger btn-sm" v-if="user_role_id==4" v-on:click="request.cur_comment=''"><i class="fa fa-times"></i></button>
                    <span v-if="user_role_id!=4 && request.done_work!=''">{{request.done_work}}</span>
                </div>
            </div>
            <div class="block  col-lg-4 col-md-4 col-sm-6 float-left img_container">
                <span class="float-left">Фото  : </span>
                <img v-if="request.file_path" v-for="path in request.file_path" v-bind:src="path" class="thumb" style="width:100px;height:100px" v-on:click="el._data.cur_photo = path" data-toggle="modal" data-target="#cur_photo_dialog">
                <input v-if="request.file_path=='' && user_role_id==4" type="file" v-bind:ref="'file_'+index" v-model='cur_file_upload[index]' v-on:change="save_cur_files(request.id,index)" multiple>
            </div>
            <div class="block  col-lg-2 col-md-2 col-sm-2 float-left">
                <button class="btn btn-success btn-sm" v-if="user_role_id==3 && request.user_check_date=='' && request.date_done!=''" v-on:click="update_check_date(request.id,index,'user_check_date')"><i class="fa fa-check"></i>
                </button>
                <button class="btn btn-success btn-sm" v-if="user_role_id==2 && request.common_date=='' && request.user_check_date!=''" v-on:click="update_check_date(request.id,index,'common_date')"><i class="fa fa-check"></i></button>
            </div>
        </div>

        <paginator v-bind:pages="pages"></paginator>
    </div>
    <?php $this->load->view("work/gallery_modal") ?>
    <?php $this->load->view("work/add_work_modal") ?>
</div>

<script src="/resources/js/components.js"></script>
<script src="https://unpkg.com/vue-pure-lightbox/dist/VuePureLightbox.umd.min.js"></script>
<script type="text/javascript">
    el = new Vue({
        el: "#request_controller",
        components: {
            'vue-pure-lightbox': window.VuePureLightbox,
        },
        data: {
            options: {
                // https://momentjs.com/docs/#/displaying/
                format: 'DD.MM.YYYY',
                useCurrent: false,
                showClear: true,
                showClose: true,
            },
            cur_comment: [],
            page_number: 0,
            current_page: 1,
            total_rows: <?=$total_rows?>,
            per_page: 25,
            pages: <?=$total_rows > 25 ? '[1,2,3]' : '[]' ?>,
            user_role_id: <?=$role_id?>,
            date_from: '',
            date_to: '',
            search_object_id: [],
            error: "",
            file_1: "",
            cur_file_upload: [],
            cur_photo: "",
            current_status: "all",
            new_job: {
                type_id: '',
                object_id: '',
                description: '',
                date_done: '',
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
                    add_user_name: '<?=$row->add_user_name?>',
                    date_add: '<?= $row->date_add?>',
                    object_name: '<?=$row->object_name?>',
                    file_path: [
                        <?php foreach(explode("||", $row->file_path) as $cur_file):?>
                        <?php if(!empty($cur_file)):?>
                        '<?="./" . $cur_file?>',
                        <?php endif;?>
                        <?php endforeach;?>
                    ],
                    done_work: '<?=!empty($row->done_work) ? $row->done_work : ""?>',
                    user_done_date: '<?=!empty($row->user_done_date) ? $row->user_done_date : ""?>',
                    done_user: '<?=$row->done_user?>',
                    user_check_date: '<?=!empty($row->user_check_date) ? $row->user_check_date : ""?>',
                    check_user: '<?=$row->check_user?>',
                    common_date: '<?=!empty($row->common_date) ? $row->common_date : ""?>',
                    common_check_user: '<?=$row->common_check_user?>',
                },
                <?php endforeach;?>
            ]
        },
        methods: {
            add_new_job: function (new_job) {
                document.querySelector("#close_add_job").click();
                let error_file_message = "Недопустимое расширение файла! Допускается pdf,gif, jpg,png"
                let file_max_size = "Размер файла не должен превышать 10МБ";

                var errors = this.check_form(new_job)
                if (errors.length > 0) {
                    this.error = errors.join(" ")
                    return;
                }
                let is_exist = false;
                let formData = new FormData()
                if (this._data.user_role_id == 4) {
                    if (this._data.file_1) {
                        let length = this.$refs.file.files.length
                        for (let i = 0; i < length; i++) {
                            formData.append('file' + i, this.$refs.file.files[i])
                            let file = this.$refs.file.files[i];
                            if (file.size > 10 * 1024 * 1024) {
                                alert(file_max_size);
                                return;
                            }
                            if (!this.check_extension(file.name)) {
                                alert(error_file_message);
                                return;
                            }
                        }
                        is_exist = this.$refs.file.value;
                    }
                }
                axios.post("/work/add_new_job", {
                    type: this._data.new_job.type_id,
                    description: this._data.new_job.description,
                    object_id: this._data.new_job.object_id,
                    date_done: this._data.new_job.date_done,
                }).then(function (result) {
                    switch (result.data.status) {
                        case 200:
                            if (is_exist) {
                                axios.post("/work/upload_file/" + result.data.request_id, formData,
                                    {
                                        headers: {
                                            'Content-Type': 'multipart/form-data'
                                        }
                                    }).then(function (response) {
                                    switch (response.data.status) {
                                        case 200:
                                            document.querySelector("#close_add_job").click();
                                            alert("Успешно добавлено!");
                                            break;
                                        default:
                                            alert(response.data.message)
                                            break;
                                    }
                                }, (error) => {
                                    alert("Ошибка обращения к серверу!")
                                });
                            } else {
                                alert("Успешно добавлено!");
                            }
                            el.search(0);
                            break;
                        case 300:
                            alert(result.data.message);
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })
            },
            save_cur_files: function (id, index) {
                let error_file_message = "Недопустимое расширение файла! Допускается pdf,gif, jpg,png"
                let file_max_size = "Размер файла не должен превышать 10МБ";
                let formData = new FormData()
                if (this._data.user_role_id == 4) {
                    if (this.$refs['file_' + index]) {
                        let length = this.$refs['file_' + index][0].files.length
                        for (let i = 0; i < length; i++) {
                            formData.append('file' + i, this.$refs['file_' + index][0].files[i])
                            let file = this.$refs['file_' + index][0].files[i];
                            if (file.size > 10 * 1024 * 1024) {
                                alert(file_max_size);
                                return;
                            }
                            if (!this.check_extension(file.name)) {
                                alert(error_file_message);
                                return;
                            }
                        }
                        is_exist = this.$refs['file_' + index][0].files[0].value;
                    } else {
                        return
                    }
                } else {
                    return
                }
                axios.post("/work/upload_file/" + id, formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function (response) {
                    switch (response.data.status) {
                        case 200:
                            alert("Успешно добавлено!");
                            el.search()
                            break;
                        default:
                            alert(response.data.message)
                            break;
                    }
                }, (error) => {
                    alert("Ошибка обращения к серверу!")
                });
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
            check_extension: function (file_name) {
                var allowed_list = ["pdf", "gif", "jpg", "png"];
                var file_name_arr = file_name.split(".");
                var ext = file_name_arr[file_name_arr.length - 1]
                var res = true;
                if (!allowed_list.includes(ext)) {
                    res = false
                }
                return res;
            },
            search: function (page = 0) {

                axios.post("/work/search/" + page, {
                    objects_id: this.search_object_id,
                    date_from: this.date_from,
                    date_to: this.date_to,
                    current_status: this.current_status,
                }).then(function (result) {
                    switch (result.data.status) {
                        case 200:
                            el._data.requests.splice()
                            let tmp_file_path = [];
                            for (var z in result.data.content) {
                                if (result.data.content[z].file_path !== null) {
                                    tmp_file_path = result.data.content[z].file_path.split('||')
                                    result.data.content[z].file_path = tmp_file_path;
                                }
                                /*var newReq = {
                                    id: result.data.content[z].id,
                                    object_name: result.data.content[z].object_name,
                                    date_add: result.data.content[z].date_add,
                                    description: result.data.content[z].description,
                                    file_path: tmp_file_path
                                }
                                el._data.requests.push(newReq);
                                tmp_file_path = []*/
                            }
                            el._data.requests = result.data.content;
                            break;
                        case 300:
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })
            },
            save_cur_comment: function (id, index) {
                axios.post("/work/save_worker_comment/" + id, {
                    comment: this.requests[index].done_work,
                }).then(function (result) {
                    switch (result.data.status) {
                        case 200:
                            el._data.requests[index].done_work = el._data.requests[index].done_work;
                            break;
                        case 300:
                            break;
                    }
                }).catch(function (e) {
                    console.log(e)
                })
            },
            update_check_date: function (id, index, type) {
                axios.post("/work/update_check_date/" + id + '/' + type, {}).then(function (result) {
                    switch (result.data.status) {
                        case 200:
                            switch (type) {
                                case 'user_check_date':
                                    el._data.requests[index].user_check_date = result.data.content;
                                    break;
                                case 'common_date':
                                    el._data.requests[index].common_date = result.data.content;
                                    break;
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