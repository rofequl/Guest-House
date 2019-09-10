<template>
    <div class="main-content-container container-fluid px-4">
        <!-- Page Header -->
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Report</span>
                <h3 class="page-title">Debit Report</h3>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Default Light Table -->
        <div class="row">
            <div class="col">
                <div class="card card-small mb-4">
                    <div class="card-header border-bottom">
                        <div class="row">
                            <div class="col">
                                <date-picker placeholder="Select" v-model="form.date" @dp-change="ChangeDepartment()" :config="options"></date-picker>
                            </div>
                            <div class="col">
                                <Select2 v-model="form.month" :options="month"
                                         :settings="{placeholder: 'Select Month', theme: 'bootstrap'}"
                                         @change="ChangeDepartment()"/>
                            </div>
                            <div class="col">
                                <button @click="FromReset()" class="btn btn-warning float-left">
                                    Reset Search</i>
                                </button>
                                <button v-print="'#printTable'" class="btn btn-success float-right">
                                    <i class="fa fa-print"> Print</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 pb-3 text-center">
                        <perfect-scrollbar>
                            <table class="table table-bordered mb-0" id="printTable">
                                <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="border-0">SN</th>
                                    <th scope="col" class="border-0">Date</th>
                                    <th scope="col" class="border-0">Voucher No</th>
                                    <th scope="col" class="border-0">Expenditure Head</th>
                                    <th scope="col" class="border-0">Amount</th>
                                    <th scope="col" class="border-0">Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="dedit in dedits.data" v-bind:key="dedit.id">
                                    <td>{{dedit.id}}</td>
                                    <td>{{dedit.date | moment}}</td>
                                    <td>{{dedit.voucher_no}}</td>
                                    <td>{{dedit.expenditure.name}}</td>
                                    <td>{{dedit.amount}}</td>
                                    <td>{{dedit.remarks}}</td>
                                </tr>
                                <tr v-if="action">
                                    <td colspan="4"><h4 class="text-right">Total Amount</h4></td>
                                    <td><h4>{{totalAmount}}</h4></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </perfect-scrollbar>
                    </div>
                    <div class="card-footer">
                        <pagination v-if="!action" :data="dedits" @pagination-change-page="getResults">
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
    export default {
        data() {
            return {
                month: [],
                dedits: {},
                totalAmount: '',
                action: false,
                form: new Form({
                    date: '',
                    month: '',
                    department_id: '',
                }),
                options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,
                }
            }
        },
        methods: {
            loadDedit() {
                axios.get('api/debit').then(({data}) => (this.dedits = data));
            },
            getResults(page = 1) {
                axios.get('api/debit?page=' + page)
                    .then(response => {
                        this.dedits = response.data;
                    });
            },
            loadMonth() {
                var that = this;
                axios.get('api/debit-month').then(function (data) {
                    that.month = _.map(data.data, function (data) {
                        var pick = _.pick(data, 'month','year');
                        var object = {id: pick.month+'-'+pick.year, text: pick.year+'-'+moment(pick.month, 'M').format('MMMM')};
                        //console.log(moment(pick.month, 'M').format('MMMM'))
                        return object;
                    })
                });
            },
            ChangeDepartment() {
                this.$Progress.start()
                this.form.post('api/debit-report')
                    .then((data) => {
                        this.dedits = data.data;
                        if (data.data.action === 'show'){
                            this.action = true;
                            this.totalAmount = data.data.sum;
                        }
                        this.$Progress.finish()
                    })
                    .catch(() => {
                        this.$Progress.fail()
                    })
            },
            FromReset(){
                this.form.reset();
                this.action = false
                Fire.$emit('AfterCreate')
            }
        },
        created() {
            this.loadDedit();
            this.loadDedit();
            this.loadMonth();
            Fire.$on('AfterCreate', () => {
                this.loadDedit();
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
