import register from './components/auth/Register'
import login from './components/auth/Login'
import home from './components/Home'
import RoomSetup from './components/room/RoomSetup'
import booking from './components/booking/Booking'
import BookingSchedule from './components/booking/BookingSchedule'
import NewBooking from './components/booking/NewBooking'
import Expenditure from './components/account/Expenditure'
import IncomeSource from './components/account/IncomeSource'
import Payment from './components/account/Payment'
import Credit from './components/entry/CreditEntry'
import Debit from './components/entry/DebitEntry'
import CreditReport from './components/report/CreditReport'
import DebitReport from './components/report/DebitReport'
import PaymentReport from './components/report/PaymentReport'
import BalanceSheet from './components/report/BalanceSheet'
import BookingView from './components/booking/BookingView'

export const routes = [
    {
        path: '/',
        component: home
    },
    {
        path: '/login',
        component: login
    },
    {
        path: '/register',
        component: register
    },
    {
        path: '/room-setup',
        component: RoomSetup
    },
    {
        path: '/booking',
        component: booking
    },
    {
        path: '/booking-schedule',
        component: BookingSchedule
    },
    {
        path: '/new-booking',
        component: NewBooking
    },
    {
        path: '/expenditure',
        component: Expenditure
    },
    {
        path: '/income-source',
        component: IncomeSource
    },
    {
        path: '/payment',
        component: Payment
    },
    {
        path: '/credit',
        component: Credit
    },
    {
        path: '/debit',
        component: Debit
    },
    {
        path: '/credit-report',
        component: CreditReport
    },
    {
        path: '/debit-report',
        component: DebitReport
    },
    {
        path: '/payment-report',
        component: PaymentReport
    },
    {
        path: '/balance-sheet',
        component: BalanceSheet
    },
    {
        path: '/booking-view/:id',
        component: BookingView
    }
];
