<template>
    <div id="PaymentsTable">
                <el-table v-loading="loading" :data="list" border fit highlight-current-row style="width: 100%">
                    <el-table-column align="center" label="ID" width="50">
                        <template slot-scope="scope">
                        <span>{{ scope.row.id }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Gateway">
                        <template slot-scope="scope">
                                <span>{{ scope.row.gateway }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" :label="type">
                        <template slot-scope="scope">
                                <span v-if="type == 'course'">
                                    <b v-if="scope.row.course &&  scope.row.course.hasOwnProperty('id') "> {{ scope.row.course.title }}</b>
                                </span>
                                <span v-else>
                                    <b v-if="scope.row.package && scope.row.package.hasOwnProperty('id') ">{{ scope.row.package.name }}</b>
                                </span>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="User">
                        <template slot-scope="scope">
                               <span v-if="scope.row.user && scope.row.user.hasOwnProperty('id')"> {{ scope.row.user.name }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column v-if="1==0" align="center" label="Subscribe Start">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip :content="'Booking Start '+scope.row.booking.date_start">
                                        <el-button size="mini" type="success" icon="el-icon-date"></el-button>
                                    </el-tooltip>
                                    <el-tooltip :content="'Booking End '+scope.row.booking.date_start">
                                        <el-button size="mini" type="danger" icon="el-icon-date"></el-button>
                                    </el-tooltip>
                                    <el-tooltip :content="'Payment At '+scope.row.created_at">
                                        <el-button size="mini" type="primary" icon="el-icon-date"></el-button>
                                    </el-tooltip>
                                </el-button-group>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" v-if="type == 'package'" label="Period">
                        <template slot-scope="scope">
                               <b v-if="scope.row.package && scope.row.package.hasOwnProperty('id') "> {{ scope.row.package.period }} Day </b>
                        </template>
                    </el-table-column>

                    <!--
                    <el-table-column align="center" label="Period">
                        <template slot-scope="scope">
                                <span>{{ dayDiff( scope.row.booking ) }} Day</span>
                        </template>
                    </el-table-column>
                    -->

                    <el-table-column align="center" label="Amount">
                        <template slot-scope="scope">
                            <span> {{ scope.row.cost }} SAR </span>
                        </template>
                    </el-table-column>

                    <el-table-column v-if="1 == 0" align="center" label="Answer" >
                        <template slot-scope="scope">
                                <el-popover trigger="hover" :content="scope.row.gateway_answer" >
                                    <el-button slot="reference"> Answer </el-button>
                                </el-popover>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Status">
                        <template slot-scope="scope">
                                <el-select v-model="scope.row.status"  @change="handleActive(scope.row)">
                                    <el-option v-for="(v , k) in Status" :key="k" :label="v" :value="parseInt(k)"></el-option>
                                </el-select>
                        </template>
                    </el-table-column>

                    <el-table-column align="center" label="Created at">
                        <template slot-scope="scope">
                                <span>{{ scope.row.created_at }}</span>
                        </template>
                    </el-table-column>

                    <el-table-column v-if="1==0" align="center" label="Delete">
                        <template slot-scope="scope">
                                <el-button-group>
                                    <el-tooltip  v-permission="['manage payments delete']" content="Delete" placement="top" >
                                        <el-button  size="mini" @click="handleDelete( scope.row.id , scope.row.title )" type="danger" icon="el-icon-delete"></el-button>
                                    </el-tooltip>
                            </el-button-group> 
                        </template>
                    </el-table-column>
                    </el-table>
    </div>
</template>

<script>
    import settings from '@/settings' ;
    import PaymentsResource from '@/api/ezuru/payments' ;
    const Payments = new PaymentsResource() ;
    import permission from '@/directive/permission/index.js' ;
    
    export default {
        directives : { permission } ,
        data(){
            return {
                Status : settings.paymentStatus ,
            }
        },
        props : ['list' , 'loading' , 'reload' , 'type'],
        methods : {
            reloadAgain() {
                this.$emit('reloadAgain') ;
            }, 
            handleDelete(id, name) {
                let o = this ;
                this.$confirm('This will permanently delete Payment ' + id + '. Continue?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }).then(() => {

                    Payments.destroy(id).then(response => {
                        this.$message({
                            type: 'success',
                            message: 'Delete completed',
                        });
                        o.reloadAgain();
                    }).catch(error => {
                    console.log(error);
                    });

                }).catch((e) => {
                    console.log(e);
                    this.$message({
                    type: 'info',
                    message: 'Delete canceled',
                    });
                });
            },
            handleActive : function(booking){

                        let o = this ;

                        this.$confirm('This will Update Unit Status. Continue?', 'Warning', {
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Cancel',
                        type: 'warning'
                        }).then(() => {
                            Payments.active('' , booking.status , booking.id ).then(response => {
                                    this.$message({
                                        type: 'success',
                                        message: 'Status Changed Succefully',
                                    });
                                    o.reloadAgain();
                            }).catch(error => {
                                    console.log(error);
                                    this.$message({ type: 'danger', message: error });     
                            });
                        }).catch((e) => {
                            this.$message({ type: 'info', message: 'Update canceled'});          
                        });

            },
            dayDiff(row){
                const date1 = new Date( row.date_start );
                const date2 = new Date( row.date_end );
                const diffTime = Math.abs( date2.getTime() - date1.getTime() );
                return Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
            }
        },
        computed : {
            
        }
    }
</script>
