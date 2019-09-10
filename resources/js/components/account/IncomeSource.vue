<template>
    <div class="main-content-container container-fluid px-4">
        <!-- Page Header -->
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Account Setup</span>
                <h3 class="page-title">Source of income</h3>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Default Light Table -->
        <div class="row">
            <div class="col">
                <div class="card card-small mb-4">
                    <div class="card-header border-bottom">
                        <form class="input-group col-md-6 col-lg-4 ml-auto" @submit.prevent="createExpenditure"
                              @keydown="form.onKeydown($event)">
                            <input type="text" v-model="form.name" class="form-control border"
                                   placeholder="Add New Income Source"
                                   aria-label="Add New Income Source" name="name">
                            <div class="input-group-append">
                                <button class="btn btn-primary shadow-none px-2" type="submit">
                                    <i class="material-icons">add</i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-body p-0 pb-3 text-center">
                        <perfect-scrollbar>
                            <table class="table table-bordered mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="border-0">#</th>
                                    <th scope="col" class="border-0">Income Source Name</th>
                                    <th scope="col" class="border-0">Modify</th>
                                    <th scope="col" class="border-0">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <expenditure v-for="expenditure in expenditures.data"
                                             v-bind:expenditure="expenditure"
                                             @delete-expenditure="deleteExpenditure"
                                             v-bind:key="expenditure.id"></expenditure>
                                </tbody>
                            </table>
                        </perfect-scrollbar>
                    </div>
                    <div class="card-footer">
                        <pagination :data="expenditures" @pagination-change-page="getResults">
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
            </div>


        </div>
        <!-- End Default Light Table -->
    </div>
</template>

<script>
    import expenditure from './IncomeSourceEdit';
    export default {
        data() {
            return {
                expenditures: {},
                form: new Form({
                    name: ''
                })
            }
        },
        methods: {
            loadExpenditure() {
                axios.get('api/income-source').then(({data}) => (this.expenditures = data));
            },
            getResults(page = 1) {
                axios.get('api/income-source?page=' + page)
                    .then(response => {
                        this.expenditures = response.data;
                    });
            },
            deleteExpenditure(id) {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.$Progress.start()
                        this.form.delete('api/income-source/' + id)
                            .then((data) => {
                                if (data.data.error) {
                                    swal.fire(
                                        'Can\'t Deleted!',
                                        data.data.error,
                                        'warning'
                                    )
                                } else {
                                    Fire.$emit('AfterCreate');
                                    swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                    )
                                    this.$Progress.finish()
                                }
                            })
                            .catch(() => {
                                this.$Progress.fail()
                                swal("Failed!", 'There was something wrong.', 'warning')
                            });
                    }
                })
            },
            createExpenditure() {
                this.$Progress.start()
                this.form.post('api/income-source')
                    .then(() => {
                        toast.fire({
                            type: 'success',
                            title: 'Income source create successfully'
                        })
                        this.form.reset()
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish()
                    })
                    .catch(error => {
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
        created() {
            this.loadExpenditure();
            Fire.$on('AfterCreate', () => {
                this.loadExpenditure();
            });
        },
        components: {
            expenditure
        }
    }
</script>

<style scoped>

</style>
