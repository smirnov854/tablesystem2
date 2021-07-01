Vue.component('paginator', {
    props:['pages'],
    template:
        '<div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center"><nav aria-label="Page navigation example">\n' +
        '  <ul class="pagination">\n' +
        '    <li class="page-item" v-for="page in pages"><a class="page-link" href="#" v-on:click=el.search(page)>{{page}}</a></li>\n' +
        '  </ul>\n' +
        '</nav></div>'
});