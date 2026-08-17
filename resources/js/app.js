import './bootstrap';
import 'bootstrap';
import './components';
import { createBarChart, createDoughnutChart, createAreaChart, createLineChart, createPieChart } from './charts';

window.SkulCharts = { createBarChart, createDoughnutChart, createAreaChart, createLineChart, createPieChart };

(window.__skulChartsQueue || []).forEach(function (fn) { typeof fn === 'function' && fn(); });
