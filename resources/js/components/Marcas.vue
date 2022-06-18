<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component titulo="Busca de marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component 
                                    titulo="ID" 
                                    id="inputId"
                                    id-help="IdHelp"
                                    texto-ajuda="Opcional. Informe o ID da marca"
                                   
                                >
                                    <input type="number" class="form-control" id="InputId" aria-describedby="idHelp" placeholder="ID" v-model="busca.id">
                                </input-container-component>
                            </div>
                            <div class=" col mb-3">
                                <input-container-component 
                                    titulo="Nome da marca" 
                                    id="inputNome"
                                    id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca"
                                     
                                >
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da marca" v-model="busca.nome">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end" @click="pesquisar()">Pesquisar</button>
                    </template>
                </card-component>
                <!--Fim card de busca-->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component 
                            :dados="marcas.data" 
                            :visualizar="{visivel: true, dataToggle:'modal', dataTarget:'#modalMarcaVisualizar'}"
                            :atualizar="{visivel: true, dataToggle:'modal', dataTarget:'#modalMarcaAtualizar'}"
                            :remover="{visivel: true, dataToggle:'modal', dataTarget:'#modalMarcaRemover'}"
                            :titulos="{
                                id: {titulo:'ID', tipo:'text'},
                                nome: {titulo:'Nome', tipo:'text'},
                                imagem: {titulo:'Imagem', tipo:'imagem'},
                                created_at: {titulo:'Data de criação', tipo:'data'},
                            }">
                        </table-component>
                    </template>
                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li v-for="link, key in marcas.links" :key="key" :class="link.active ? 'page-item active'  : 'page-item' " @click="paginacao(link)">
                                        <a class="page-link"  v-html="link.label"></a>
                                    </li>
                                </paginate-component>
                            </div>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                        </div>
                      
                    </template>
                </card-component>

                
                <!--Fim card de listagem de marca-->
            </div>
        </div>
        <!--Inicio modal de inclusão de marcas -->
        <modal-component id="modalMarca" titulo="Adicionar marca">
           <template v-slot:alerts>
                <alert-component :detalhes="transacaoDetalhes" tipo="success" titulo="Cadastro da marca realizado com sucesso!"  v-if="transacaoStatus == 'adicionado' "> </alert-component>
                <alert-component :detalhes="transacaoDetalhes" tipo="danger" titulo="Erro ao tentar cadastrar a marca"  v-if="transacaoStatus == 'erro' " > </alert-component>
           </template>
            <template v-slot:content>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="novoNome" id-help="novoNomeHelp" texto-ajuda="Opcional. Informe o nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da marca" v-model="nomeMarca">
                    </input-container-component>
                </div>
                
                <div class="form-group">
                    <input-container-component titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file mt-3" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="loadImage($event)">
                    </input-container-component>
                </div>
               
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>

        </modal-component> 
        <!--Fim modal de inclusão de marcas -->

        <!-- Inicio modal de visualizar marcas -->
        <modal-component id="modalMarcaVisualizar" titulo="Visualizar Marca">
            <template v-slot:alerts></template>
            <template v-slot:content>
                <input-container-component id="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                 <input-container-component id="Nome da marca">
                    <input type="text" class="form-control mb-3" :value="$store.state.item.nome" disabled>
                </input-container-component>
                 <input-container-component id="Imagem">
                   <img v-if="$store.state.item.imagem" :src="'storage/'+$store.state.item.imagem" alt="">
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
            </template>
        </modal-component>
        <!-- Fim modal de visualizar marcas -->

         <!-- Inicio modal de remoção marcas -->
        <modal-component id="modalMarcaRemover" titulo="Remover Marca">
            <template v-slot:alerts>
                <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status=='sucesso'"></alert-component>
                 <alert-component tipo="danger" titulo="Erro na transação" :detalhes="$store.state.transacao" v-if="$store.state.transacao.status=='erro'"></alert-component>
            </template>
            <template v-slot:content v-if="$store.state.transacao.status != 'sucesso'">
                <input-container-component id="ID">
                    <input type="text" class="form-control" :value="$store.state.item.id" disabled>
                </input-container-component>
                 <input-container-component id="Nome da marca">
                    <input type="text" class="form-control mb-3" :value="$store.state.item.nome" disabled>
                </input-container-component>
            </template>
            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()" v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
            </template>
        </modal-component>
        <!-- Fim modal de remoção marcas -->


        <!--Inicio modal de atualização de marcas -->
        <modal-component id="modalMarcaAtualizar" titulo="Atualizar marca">
           <template v-slot:alerts>
                <!-- <alert-component :detalhes="transacaoDetalhes" tipo="success" titulo="Cadastro da marca realizado com sucesso!"  v-if="transacaoStatus == 'adicionado' "> </alert-component>
                <alert-component :detalhes="transacaoDetalhes" tipo="danger" titulo="Erro ao tentar cadastrar a marca"  v-if="transacaoStatus == 'erro' " > </alert-component> -->
           </template>
            <template v-slot:content>
                <div class="form-group">
                    <input-container-component titulo="Nome da marca" id="atualizarNome" id-help="atualizarNomeHelp" texto-ajuda="Opcional. Informe o nome da marca">
                        <input type="text" class="form-control" id="novoNome" aria-describedby="atualizarNomeHelp" placeholder="Nome da marca" v-model="$store.state.item.nome">
                    </input-container-component>
                </div>
                
                <div class="form-group">
                    <input-container-component titulo="Imagem" id="atualizarImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                        <input type="file" class="form-control-file mt-3" id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem" @change="loadImage($event)">
                    </input-container-component>
                </div>
               
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="atualizar()">Atualizar</button>
            </template>

        </modal-component> 
        <!--Fim modal de atualização de marcas -->

        
        
    </div>
</template> 

<script>
import axios from 'axios'
   export default {
         computed: {
             token(){
                let token = document.cookie.split(';').find(indice => {
                     return indice.includes("token=")
                 })
                 
                 token = token.split('=')[1]
                 token = 'Bearer '+token
                 return token
             }
           }, 

       data() {
           return {
               urlBase: 'http://localhost:8000/api/v1/marca',
               nomeMarca: '',
               urlPagination: '',
               urlFiltro: '',
               arquivoImagem: [],
               transacaoStatus: '',
               transacaoDetalhes: {},
               marcas:{data: []},
               busca: { id: '', nome: ''},
           }
       },
       methods: {
            atualizar(){
                
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData();
                formData.append('_method', 'patch')
                formData.append('nome', this.$store.state.item.nome)
                formData.append('imagem', this.arquivoImagem[0])
                 
                 let config = {
                    headers:{ 
                        'Accept':'application/json',
                        'Content-Type':'multipart/form-data',
                        'Authorization' : this.token
                    }
                 }

                 
                
                axios.post(url,formData,config)
                .then(response => {
                    console.log('Resposta',response)
                    // this.$store.state.transacao.status="sucesso"
                    // this.$store.state.transacao.message= response.data.msg
                    this.carregarLista()
                })
                .catch(error => {
                    console.log('erro')
                })
            },
           remover(){
               let confirmacao = confirm('Tem certeza que deseja remover esse registro')
                if(!confirmacao){
                    return false;
                } 
                let url = this.urlBase + '/' + this.$store.state.item.id
                let formData = new FormData();
                formData.append('_method', 'delete')
                 let config = {
                    headers:{ 
                        'Accept':'application/json',
                        'Authorization' : this.token
                    }
                 }

                 
                
                axios.post(url,formData,config)
                .then(response => {
                    // console.log('Resposta',response.data.msg)
                    this.$store.state.transacao.status="sucesso"
                    this.$store.state.transacao.message= response.data.msg
                    this.carregarLista()
                })
                .catch(error => {
                    this.$store.state.transacao.status="erro"
                    this.$store.state.transacao.message=error.response.data.erro
                })
              console.log('Chegamos até aqui')
           },
           pesquisar(){
               let filtro = ''
               for(let chave in this.busca){
                    if(this.busca[chave]){
                        if(filtro != ''){
                            filtro += ";"
                        }
                        filtro += chave + ':like:' + this.busca[chave]
                    }
               }
               if(filtro != ''){
                   this.urlPagination = 'page=1'
                    this.urlFiltro = '&filtro='+filtro
               }else{
                   this.urlFiltro = ''
               }
               //console.log(this.busca)
               this.carregarLista()
               

           },
           paginacao(l){
               if(l.url){
                // this.urlBase = l.url
                 this.urlPagination = l.url.split('?')[1]
                 this.carregarLista()
               }
              
           },
           carregarLista(){
                let config = {
                 headers:{
                    'Accept':'application/json',
                    'Authorization' : this.token
                 }
               } 
               let url = this.urlBase + '?' + this.urlPagination + this.urlFiltro
                axios.get(url,config)
                .then(response=>{
                    console.log(response)
                     this.marcas = response.data
                    
                })
                .catch(errors=>{
                    console.log(errors)
                })
           },
           loadImage(e){
               this.arquivoImagem = e.target.files
           },
           salvar(){
              // console.log(this.nomeMarca, this.arquivoImagem)
               let formData = new FormData();
               formData.append('nome', this.nomeMarca)
               formData.append('imagem', this.arquivoImagem[0])
                
               let config = {
                 headers:{
                    'Content-Type':'multipart/form-data',
                    'Accept':'application/json',
                    'Authorization' : this.token
                 }
               } 

               axios.post(this.urlBase,formData,config)
               .then( response => {
                   this.transacaoStatus = 'adicionado'
                   this.transacaoDetalhes = {
                       message: `Marca ${response.data.nome} cadastrada`
                   }
                    this.carregarLista()
                  
               })
               .catch(errors => {
                    this.transacaoStatus = 'erro'
                    this.transacaoDetalhes = {
                        message: errors.response.data.message,
                        datas: errors.response.data.errors
                    }
                  //console.log(erros.response.data.message)
               })
           }
       },
       mounted(){
           this.carregarLista()
       }
   }
    
</script>