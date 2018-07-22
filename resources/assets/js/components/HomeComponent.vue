<template>
    <div class="container">
        <div class="row justify-content-center mb-3">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header font-weight-bold">Disk Usage Overview</div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <p>Total Size:</p>
                            </div>
                            <div class="col-md-9">
                                <p> {{total_sizes_mb}}MB ({{total_sizes_b}}B)</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <p>No of files:</p>
                            </div>
                            <div class="col-md-9">
                                <p> {{no_of_files}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card card-default">
                            <div class="card-header font-weight-bold">File Usage Compositions</div>

                            <div class="card-body">
                                <div v-if="composition.length" class="table-responsive">
                                    <table class="table">
                                         <thead>
                                             <tr>
                                                <th scope="col">Type</th>
                                                <th scope="col">No of files</th>
                                                <th scope="col">Size</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                            <tr v-for="data in composition">
                                                <td> {{data.mime_type}} </td>
                                                <td> {{data.total_files}} </td>
                                                <td> {{((data.total_sizes/1024)/1024).toLocaleString(undefined, {maximumFractionDigits:2})}}MB ({{data.total_sizes.toLocaleString()}}B) </td>
                                            </tr>
                                         </tbody>
                                    </table>
                                </div>
                                <div v-else>
                                    <p> No Data </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
</template>

<script>
export default {
    data: function(){
        return{
            total_sizes_b : 0,
            total_sizes_mb : 0.00,
            no_of_files : 0,
            composition : [],
        }
    },
    created(){
        this.fetch();
    },methods: {
        fetch : function (){
            axios.get('home')
                .then(response => {
                    this.total_sizes_b = (response.data.overview[0].total_sizes).toLocaleString();
                    this.total_sizes_mb = ((response.data.overview[0].total_sizes / 1024) / 1024).toLocaleString(undefined, {maximumFractionDigits:2});
                    this.no_of_files = (response.data.overview[0].total_files).toLocaleString();

                    this.composition = response.data.composition;
            });
        }
    }
}
</script>