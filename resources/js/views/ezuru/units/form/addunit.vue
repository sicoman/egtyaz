<template>
    <div id="addUnit">

    </div>
</template>

<script>

import UnitResource from '@/api/ezuru/units' ;

const Units = new UnitResource() ;

export default {
    data(){
        return {

        }
    },
    methods: {
        async addUnit(){

             await Units.store( { "create_update" : 1} ).then( addUnit => {
                if( addUnit && addUnit.id && addUnit.id > 0 ){
                    this.$router.push( '/ezuru/units/edit/'+addUnit.id ) ;
                }else{
                    this.$message({type: 'warning',message: 'Unable to Start Creating Unit'});
                    this.$router.push( '/ezuru/units/' ) ;
                }
            });

            
        }
    },
    mounted : function(){
        this.addUnit();
    }
}
</script>

