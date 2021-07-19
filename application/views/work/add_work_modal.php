<div id="add_job" class="modal  fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <span v-if="user_role_id!=4">Добавление заявки</span>
                    <span v-if="user_role_id==4">Добавление работ</span>
                </div>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" v-if="error">{{error}}</div>
                <div class="form-row">
                    <label class="col-lg-4 col-md-4 col-sm-4 text-right float-left">Объект</label>
                    <select class="form-control col-lg-8 col-md-8 col-sm-8" v-model="new_job.object_id" required>
                        <option v-for="{id,name} in objects" :value="id">{{name}}</option>
                    </select>
                </div>
                <div class="form-row">
                    <label class="col-lg-4 col-md-4 col-sm-4 text-right float-left">Тип работ</label>
                    <select class="form-control col-lg-8 col-md-8 col-sm-8" v-model="new_job.type_id" required>
                        <option v-for="{id,name} in type" :value="id">{{name}}</option>
                    </select>
                </div>
                <div class="form-row">
                    <label class="col-lg-4 col-md-4 col-sm-4 text-right float-left">Описание</label>
                    <textarea v-model="new_job.description" class="form-control col-lg-8 col-md-8 col-sm-8"></textarea>
                </div>

                <div class="form-row" v-if="user_role_id==4">
                    <label class="col-lg-4 col-md-4 col-sm-4 text-right float-left">Дата выполнения работ</label>
                    <date-picker class="form-control col-lg-8 col-md-8 col-sm-8 float-left datepicker" v-model='new_job.date_done' :config='options'></date-picker>
                </div>
                <div class="form-row col-lg-12 col-md-12 col-sm-12 float-left my-2" v-if="user_role_id==4">
                    <label class="col-lg-2 col-md-2 col-sm-2 text-right" title="Максимальный размер 10 мб">Фото</label>
                    <input type="file" ref='file' v-model="file_1" multiple>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger close_dialog float-left" data-dismiss="modal" id="close_add_job">Закрыть</button>
                <button class="btn btn-success float-right" id="confirm_add_user" v-on:click="add_new_job(new_job)">Добавить</button>
            </div>
        </div>
    </div>
</div>