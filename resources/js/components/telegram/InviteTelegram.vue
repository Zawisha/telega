<template>
    <div class="container telegram_marg">
        <div class="d-inline-flex align-items-center">
            <button type="button" class="btn btn-warning telegram_lit_marg" v-on:click="addStrokaTelegramInvite()">Добавить строку(обнови страницу)</button>
            <button type="button" class="btn btn-success telegram_lit_marg otst_l" v-on:click="startInvite()">Старт</button>
<!--            <button type="button" class="btn btn-success telegram_lit_marg otst_l" v-on:click="test()">Test</button>-->
            <div v-if="show_green" class="ml-2 renew ">
                <span class="green_color">Обновлено</span>
            </div>
            <div v-if="in_progress!==''" class="ml-2 renew ">
                <span>В работе:{{ in_progress }}</span>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th scope="col" class="do_col_group">Группа(@group)</th>
                <th scope="col">Номер(+375298888888)</th>
                <th scope="col">В базе</th>
                <th scope="col" class="check_do">Включить</th>
                <th scope="col" class="check_do">Комментарий</th>
                <th scope="col" class="check_do">Удалить строку</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(group, index) in linesInvite">
                <td>
                    <select v-model="group.group_id" @blur="upDataTelegaLine(group.id,'group_id',group.group_id)">
                        <option v-for="group in groupsList" :value="group.id">{{ group.group_name }}</option>
                    </select>
                </td>
                <td>
                    <select v-model="group.phone_id" @blur="upDataTelegaLine(group.id,'phone_id',group.phone_id)">
                        <option v-for="phone in phoneList" :value="phone.id">{{ phone.phone }}</option>
                    </select>
                </td>
                <td>{{ group.group?.countUsers ?? 0 }}</td>
                <td><input
                    class="form-check-input check_do"
                    type="checkbox"
                    value=""
                    v-model="group.vkl"
                    @blur="upDataTelegaLine(group.id,'vkl',group.vkl)">
                </td>
                <td>
                    <textarea type="text" rows="3" class="auto_input_height textar_width" ref="auto_input"  v-model="group.comment" @blur="upDataTelegaLine(group.id,'comment',group.comment)" />
                </td>
                <td v-on:click="deleteLine(group.id)" class="deleteCursor">Удалить</td>

            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

export default {
    props: {
        transfer: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            linesInvite:this.transfer.lines,
            groupsList:this.transfer.groups,
            phoneList:this.transfer.phones,
            show_green:false,
            in_progress:''
        }
    },

    methods: {
        test()
        {

          console.log(this.linesInvite)
        },
        setInProgress(id)
        {
            for(let i = 0; i < (this.phoneList.length); i++)
            {
                if(this.phoneList[i]['id']===id)
                {
                    this.in_progress=this.phoneList[i]['phone'];
                }
            }

        },
        startInvite()
        {
            for(let i = 0; i < (this.linesInvite.length); i++)
            {
                if(this.linesInvite[i]['vkl']===true)
                {
                    this.setInProgress(this.linesInvite[i]['phone_id']);
            axios
                .post('/startInvite',{
                    group_id: this.linesInvite[i]['group_id'],
                    phone_id: this.linesInvite[i]['phone_id'],
                })
                .then(response => {
                    // Успешный ответ
                    if(response.data.message)
                    {
                        let mes=response.data.countUsers+':'+response.data.message;
                        this.dopisComment(i,mes);
                        this.upDataTelegaLine(this.linesInvite[i]['id'],'comment',response.data.countUsers);
                        this.in_progress='';
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Обработка ошибок валидации
                        alert(this.errorsToString(error.response.data.errors));
                        this.in_progress='';
                    } else {
                        // Обработка других ошибок
                        //alert(error.response.data.message);
                        this.dopisComment(i,error.response.data.message);
                        this.upDataTelegaLine(this.linesInvite[i]['id'],'comment',error.response.data.message);
                        this.in_progress='';
                    }
                });
                }
            }
        },
        dopisComment(i,newText)
        {
            if(this.linesInvite[i]['comment']==null)
            {
                this.linesInvite[i]['comment']=newText;
            }
            else
            {
                this.linesInvite[i]['comment']=this.linesInvite[i]['comment']+', '+newText;
            }
        },
        showHideGreen()
        {
            this.show_green=true
            setTimeout(() => {
                this.show_green = false; // Здесь this будет ссылаться на правильный контекст
            }, 2000);
        },
        addStrokaTelegramInvite()
        {
            axios
                .post('/addStrokaTelegramInvite',{
                })
                .then(response => {
                    // Успешный ответ
                    if(response.data.message)
                    {
                        alert(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Обработка ошибок валидации
                        alert(this.errorsToString(error.response.data.errors));
                    } else {
                        // Обработка других ошибок
                        alert(error.response.data.message);
                    }
                });
        },
        upDataTelegaLine(id,where,data)
        {
            axios
                .post('/upDataTelegaLine',{
                    id: id,
                    where: where,
                    data: data,
                })
                .then(response => {
                    // Успешный ответ
                    if(response.data.message)
                    {
                        if(where==='group_id')
                        {
                            this.getInBaseCount(id,data);
                        }
                    }
                    this.showHideGreen();
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Обработка ошибок валидации
                        alert(this.errorsToString(error.response.data.errors));
                    } else {
                        // Обработка других ошибок
                        alert(error.response.data.message);
                    }
                });
        },
        getInBaseCount(id,data)
        {
            axios
                .post('/getInBaseCount',{
                    id: data,
                })
                .then(response => {
                    // Успешный ответ
                    if(response.data.message)
                    {
                        this.linesInvite.forEach(oneLine => {
                            if (oneLine.id === id) {
                                if (oneLine.group==null) {
                                    let objToPush= {};
                                    objToPush['countUsers'] = response.data.count;
                                    oneLine.group=objToPush;
                                }
                                else
                                {
                                    oneLine.group.countUsers=response.data.count;
                                }
                            }

                        });
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Обработка ошибок валидации
                        alert(this.errorsToString(error.response.data.errors));
                    }
                });
        },
        deleteLine(id)
        {
            // Отображаем диалоговое окно с подтверждением
            const userConfirmed = confirm("Вы уверены, что хотите удалить эту строку?");

            if (userConfirmed) {
                axios
                    .post('/deleteLine',{
                        id:id,
                    })
                    .then(response => {
                        // Успешный ответ
                        this.linesInvite=this.linesInvite.filter(item => item.id !== id)
                        alert(response.data.message);
                    })
                    .catch(error => {
                        if (error.response && error.response.status === 422) {
                            // Обработка ошибок валидации
                            alert(this.errorsToString(error.response.data.errors));
                        } else {
                            // Обработка других ошибок
                            alert(error);
                        }
                    });
            }
        }

    }
}
</script>
