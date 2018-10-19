<template>
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">各类型商品占比图</div>
                <div class="panel-body">
                    <chart :options="typePie" style="width: 100%;height:300px;"></chart>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">商品库存情况</div>
                <div class="panel-body">
                    <chart :options="inventoryTopTen" style="width: 100%;height:300px;"></chart>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.loadTypePieData();
            this.loadInventoryData();
        },
        data() {
            return {
                typePieData: {
                    seriesData: {
                        legend: [],
                        data: []
                    }
                },
                inventoryData: {
                    yAxis: [],
                    data: []
                }
            }
        },
        methods: {
            // 载入类型饼状图
            loadTypePieData() {
                this.$api.get('/home/type_pie')
                    .then(response => {
                        this.typePieData.seriesData = response.data;
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    })
            },
            // 载入库存前十排行
            loadInventoryData() {
                this.$api.get('/home/inventory_top_ten')
                    .then(response => {
                        this.inventoryData = response.data;
                    })
                    .catch(error => {
                        this.$notify.error({message: this.$error2message(error)});
                    })
            }

        },
        computed: {
            // 类型饼状图
            typePie() {
                let vm = this;
                return {
                    title: {
                        text: '',
                        subtext: '',
                        x: 'center'
                    },
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    legend: {
                        orient: 'vertical',
                        x: 'right',
                        data: vm.typePieData.seriesData.legend
                    },

                    calculable: false,
                    series: [
                        {
                            name: '商品占比',
                            type: 'pie',
                            radius: '59.5%',
                            center: ['40%', '60%'],
                            data: vm.typePieData.seriesData.data
                        }
                    ]
                }
            },
            // 库存前十排行
            inventoryTopTen() {
                let vm = this;
                return {
                    grid: {
                        top: '5%',
                        left: '3%',
                        right: '8%',
                        bottom: '10%',
                        containLabel: true
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {type: 'shadow'},
                        formatter(params, ticket, callback) {
                            if (params[0].value !== undefined) {
                                return `${params.map(param => `<span>${param.seriesName}</span><br/>
							 	<span>${param.name}</span>：${param.value == null ? `/` : `${param.value} 副`}
							 `).join('<br>')}`;
                            }
                        }
                    },
                    xAxis: [
                        {
                            name: '副',
                            boundaryGap: false,
                            axisLine: {show: true, lineStyle: {color: '#'}},
                            axisLabel: {formatter: `{value}`}
                        }
                    ],
                    yAxis: {
                        axisTick: {show: false},
                        axisLine: {show: false},
                        data: vm.inventoryData.yAxis,
                        axisLabel: {
                            formatter: function () {
                                return "";
                            }
                        }
                    },
                    series: [
                        {
                            type: 'bar',
                            itemStyle: {
                                normal: {color: 'rgba(0, 0, 0, 0)'}
                            },
                            barGap: '-100%',
                            animation: false,
                        },
                        {
                            name: '库存',
                            type: 'bar',
                            label: {
                                normal: {
                                    show: true,
                                    position: 'insideLeft',
                                    color: '#000',
                                    textBorderColor: 'rgba(225, 225, 225, .9)',
                                    textBorderWidth: 2,
                                    textShadowBlur: 2,
                                    formatter(params, ticket, callback) {
                                        return `${params.name}`;
                                    },
                                }
                            },
                            itemStyle: {
                                normal: {
                                    color: function (params) {
                                        let colorList = [
                                            '#ffdcd2', '#ffc7b7', '#ffb39e', '#fd9f85', '#f78c6e',
                                            '#ed7a5a', '#e16f4f', '#d25c3b', '#c44b29', '#b83915',
                                            //色彩保留备用
                                            // '#fcced0', '#ffb2ae', '#ff9693', '#ff8682', '#ff6f6a',
                                            // '#ff5d50', '#f04038', '#d72624', '#c61817', '#ad1211',
                                        ];
                                        return colorList[params.dataIndex]
                                    },
                                }
                            },
                            data: vm.inventoryData.data
                        },
                    ],
                }
            }
        }
    }
</script>
