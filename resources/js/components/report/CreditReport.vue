<template>
    <div class="main-content-container container-fluid px-4">
        <!-- Page Header -->
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Report</span>
                <h3 class="page-title">Credit Report</h3>
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
                                <date-picker v-model="form.date" placeholder="Select date" @dp-change="ChangeDepartment()"
                                             :config="options"></date-picker>
                            </div>
                            <div class="col">
                                <Select2 v-model="form.month" :options="month"
                                         :settings="{placeholder: 'Select Month', theme: 'bootstrap'}"
                                         @change="ChangeDepartment()"/>
                            </div>
                            <div class="col">

                            </div>
                            <div class="col">
                                <button @click="FromReset()" class="btn btn-warning float-left">
                                    Reset Search
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
                                    <th scope="col" class="border-0">Source of Income</th>
                                    <th scope="col" class="border-0">Amount</th>
                                    <th scope="col" class="border-0">Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="credit in credits.data" v-bind:key="credit.id">
                                    <td>{{credit.id}}</td>
                                    <td>{{credit.date | moment}}</td>
                                    <td>{{credit.voucher_no}}</td>
                                    <td>{{credit.income_source == null?'Booking': credit.income_source.name}}</td>
                                    <td>{{credit.amount}}</td>
                                    <td>{{credit.remarks}}</td>
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
                        <pagination v-if="!action" :data="credits" @pagination-change-page="getResults">
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
                credits: {},
                totalAmount: '',
                action: false,
                form: new Form({
                    date: '',
                    month: '',
                }),
                options: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,
                }
            }
        },
        methods: {
            loadCredit() {
                axios.get('api/credit').then(({data}) => (this.credits = data));
            },
            getResults(page = 1) {
                axios.get('api/credit?page=' + page)
                    .then(response => {
                        this.credits = response.data;
                    });
            },
            loadMonth() {
                var that = this;
                axios.get('api/credit-month').then(function (data) {
                    that.month = _.map(data.data, function (data) {
                        var pick = _.pick(data, 'month', 'year');
                        var object = {
                            id: pick.month + '-' + pick.year,
                            text: pick.year + '-' + moment(pick.month, 'M').format('MMMM')
                        };
                        //console.log(moment(pick.month, 'M').format('MMMM'))
                        return object;
                    })
                });
            },
            ChangeDepartment() {
                this.$Progress.start()
                this.form.post('api/credit-report')
                    .then((data) => {
                        console.log(data)
                        this.credits = data.data;
                        if (data.data.action === 'show') {
                            this.action = true;
                            this.totalAmount = data.data.sum;
                        }
                        this.$Progress.finish()
                    })
                    .catch(() => {
                        this.$Progress.fail()
                    })
            },
            FromReset() {
                this.form.reset();
                this.action = false
                Fire.$emit('AfterCreate')
            }
        },
        created() {
            this.loadCredit();
            this.loadMonth();
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
