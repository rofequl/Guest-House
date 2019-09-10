<template>
    <div class="main-content-container container-fluid px-4">
        <!-- Page Header -->
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Debit Setup</span>
                <h3 class="page-title">Debit Entry</h3>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Default Light Table -->
        <div class="row">
            <div class="col">
                <div class="card card-small mb-4">
                    <div class="card-header border-bottom">
                        <div class="input-group col-md-6 col-lg-4 ml-auto">
                            <button class="btn btn-primary ml-auto" @click="NewModal"><i class="fas fa-plus mr-2"></i>
                                Debit Entry
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0 pb-3 text-center">
                        <perfect-scrollbar>
                            <table class="table table-bordered mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="border-0">Sr</th>
                                    <th scope="col" class="border-0">Voucher No</th>
                                    <th scope="col" class="border-0">Date</th>
                                    <th scope="col" class="border-0">Expenditure Head</th>
                                    <th scope="col" class="border-0">Payment Method</th>
                                    <th scope="col" class="border-0">Amount</th>
                                    <th scope="col" class="border-0">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="credit in credits.data" v-bind:key="credit.id">
                                    <td>{{credit.id}}</td>
                                    <td>{{credit.voucher_no}}</td>
                                    <td>{{credit.date | moment}}</td>
                                    <td>{{credit.expenditure.name}}</td>
                                    <td>{{credit.payment.name}}</td>
                                    <td>{{credit.amount}}</td>
                                    <td>
                                        <div class="btn-group d-inline-flex mx-auto" role="group"
                                             aria-label="Basic example">
                                            <button type="button" @click="NewModalUpdate(credit)"
                                                    class="btn btn-sm btn-white"><i
                                                class="fas fa-edit mr-1"></i>
                                            </button>
                                            <button type="button" @click="deleteCredit(credit.id)"
                                                    class="btn btn-sm btn-white"><i
                                                class="fas fa-trash mr-1"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </perfect-scrollbar>
                    </div>
                    <div class="card-footer">
                        <pagination :data="credits" @pagination-change-page="getResults">
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Default Light Table -->

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateCredit() : createCredit()"
                          @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <date-picker v-model="form.date" :config="options"></date-picker>
                                    <has-error :form="form" field="date"></has-error>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" v-model="form.voucher_no" name="voucher_no" class="form-control"
                                           id="voucher_no" value="ok"
                                           placeholder="Voucher No"
                                           :class="{ 'is-invalid': form.errors.has('voucher_no') }" readonly>
                                    <has-error :form="form" field="voucher_no"></has-error>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <select class="form-control" v-model="form.expenditure_id" name="expenditure_id"
                                            :class="{ 'is-invalid': form.errors.has('expenditure_id') }">
                                        <option value="" selected="" disabled>Choose Ependiture Head</option>
                                        <option v-for="expenditure in expenditures"
                                                v-bind:value="expenditure.id">{{expenditure.name}}
                                        </option>
                                    </select>
                                    <has-error :form="form" field="expenditure_id"></has-error>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="input-group input-group-seamless">
                                        <input type="number" v-model.number="form.amount" name="amount"
                                               class="form-control"
                                               id="amount" min="0"
                                               placeholder="Amount"
                                               :class="{ 'is-invalid': form.errors.has('amount') }">
                                        <span class="input-group-append">
                                          <span class="input-group-text">&#2547;</span>
                                        </span>
                                    </div>
                                    <has-error :form="form" field="amount"></has-error>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <select class="form-control"
                                            v-model="form.payment_id" name="payment_id"
                                            :class="{ 'is-invalid': form.errors.has('payment_id') }">
                                        <option value="" selected="" disabled>Choose Payment Method</option>
                                        <option v-for="payment in payments"
                                                v-bind:value="payment.id">{{payment.name}}
                                        </option>
                                    </select>
                                    <has-error :form="form" field="payment_id"></has-error>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" v-model="form.remarks" name="remarks" class="form-control"
                                           id="validationServer01"
                                           placeholder="Remarks" :class="{ 'is-invalid': form.errors.has('remarks') }">
                                    <has-error :form="form" field="remarks"></has-error>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" v-if="editMode" class="btn btn-primary">Update</button>
                            <button type="submit" v-if="!editMode" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                payments: {},
                expenditures: {},
                credits: {},
                editMode: false,
                form: new Form({
                    id: '',
                    date: '',
                    voucher_no: '',
                    expenditure_id: '',
                    payment_id: '',
                    amount: '',
                    remarks: ''
                }),
                date: new Date(),
                options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,
                }
            }
        },
        methods: {
            NewModal() {
                this.form.reset();
                this.form.clear();
                this.editMode = false;
                this.form.date = this.date;
                this.form.voucher_no = Math.floor(Math.random() * 899) + 100 + '' + this.credits.total;
                $('#addNew').modal('show');
                $('#addNewLabel').html('Insert Debit entry');
            },
            NewModalUpdate(data) {
                this.form.reset();
                this.form.clear();
                this.form.fill(data);
                this.form.date = moment(data.date).format('DD/MM/YYYY');
                this.editMode = true;
                $('#addNew').modal('show');
                $('#addNewLabel').html('Update Debit entry');
            },
            updateCredit() {
                this.$Progress.start()
                this.form.put('api/debit/' + this.form.id)
                    .then(() => {
                        toast.fire({
                            type: 'success',
                            title: 'Debit update successfully'
                        })
                        this.form.reset()
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish()
                        $('#addNew').modal('hide');
                    })
                    .catch(() => {
                        this.$Progress.fail()
                        swal("Failed!", 'There was something wrong.', 'warning')
                    })
            },
            loadExpenditure() {
                axios.get('api/expenditure-all').then(({data}) => (this.expenditures = data));
            },
            loadPayment() {
                axios.get('api/payment-all').then(({data}) => (this.payments = data));
            },
            loadCredit() {
                axios.get('api/debit').then(({data}) => (this.credits = data));
            },
            getResults(page = 1) {
                axios.get('api/debit?page=' + page)
                    .then(response => {
                        this.credits = response.data;
                    });
            },
            createCredit() {
                this.$Progress.start()
                this.form.post('api/debit')
                    .then(() => {
                        toast.fire({
                            type: 'success',
                            title: 'Debit entry successfully'
                        })
                        this.form.reset()
                        Fire.$emit('AfterCreate')
                        this.$Progress.finish()
                        $('#addNew').modal('hide');
                    })
                    .catch(() => {
                        this.$Progress.fail()
                        swal("Failed!", 'There was something wrong.', 'warning')
                    })
            },
            deleteCredit(id) {
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
                        this.form.delete('api/debit/' + id)
                            .then((data) => {
                                Fire.$emit('AfterCreate');
                                swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                                this.$Progress.finish()
                            })
                            .catch(() => {
                                this.$Progress.fail()
                                swal("Failed!", 'There was something wrong.', 'warning')
                            });
                    }
                })
            },
        },
        created() {
            this.loadCredit();
            this.loadExpenditure();
            this.loadPayment();
            Fire.$on('AfterCreate', () => {
                this.loadCredit();
            });
        },
        filters: {
            moment: function (date) {
                return moment(date).format('Do MMM, YYYY');
            }
        }
    }
</script>

<style scoped>

</style>
