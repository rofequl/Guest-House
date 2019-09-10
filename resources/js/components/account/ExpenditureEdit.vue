<template>
    <tr>
        <td>{{expenditure.id}}</td>
        <td>
            <input type="text" class="form-control" v-model="form.name" v-if="edit">
            <span v-else>{{expenditure.name}}</span>
        </td>
        <td>{{expenditure.updated_at | moment}}</td>
        <td>
            <div class="btn-group d-table mx-auto" role="group" aria-label="Basic example">
                <button type="button" @click='editExpenditure' v-if="!edit" class="btn btn-sm btn-white"><i
                    class="fas fa-edit mr-1"></i>Edit
                </button>
                <button type="button" @click='updateExpenditure(expenditure.id)' v-if="edit" class="btn btn-sm btn-white">
                    <i class="fas fa-check-square mr-1"></i>Update
                </button>
                <button type="button" @click='editExpenditureCancel' v-if="edit" class="btn btn-sm btn-white">
                    <i class="fas fa-window-close mr-1"></i>Cancel
                </button>
                <button type="button" @click="$emit('delete-expenditure',expenditure.id)" v-if="!edit"
                        class="btn btn-sm btn-white"><i
                    class="fas fa-trash mr-1"></i>Delete
                </button>
            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        props: ['expenditure'],
        data() {
            return {
                edit: false,
                form: new Form({
                    name: ''
                })
            }
        },
        methods: {
            editExpenditure() {
                this.edit = true;
                this.form.name = this.expenditure.name;
            },
            editExpenditureCancel() {
                this.edit = false;
                this.form.name = '';
            },
            updateExpenditure(id) {
                this.$Progress.start()
                this.form.put('api/expenditure/'+id)
                    .then(() => {
                        toast.fire({
                            type: 'success',
                            title: 'Expenditure create successfully'
                        })
                        Fire.$emit('AfterCreate');
                        this.$Progress.finish();
                        this.editExpenditureCancel();
                    })
                    .catch (error => {
                        let data = error.response;
                        if (data.status === 422) {
                            let allData = '', mainData = '';
                            $.each(data.data.errors, function (key, value) {
                                if ($.isPlainObject(value)) {
                                    $.each(value, function (key, value) {
                                        allData += value + "<br/>";
                                    });
                                } else {
                                    mainData += value + "<br/>";
                                }
                            });
                            swal.fire({
                                title: 'Warning',
                                html: mainData,
                                type: 'error'
                            })
                            this.$Progress.fail()
                        }
                    })
            }
        },
        filters: {
            moment: function (date) {
                return moment(date).format('MMMM Do, YYYY');
            }
        }
    }
</script>

<style scoped>

</style>
