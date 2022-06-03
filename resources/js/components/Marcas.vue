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
                                    <input type="number" class="form-control" id="InputId" aria-describedby="idHelp" placeholder="ID">
                                </input-container-component>
                            </div>
                            <div class=" col mb-3">
                                <input-container-component 
                                    titulo="Nome da marca" 
                                    id="inputNome"
                                    id-help="nomeHelp"
                                    texto-ajuda="Opcional. Informe o nome da marca"
                                >
                                    <input type="text" class="form-control" id="inputNome" aria-describedby="nomeHelp" placeholder="Nome da marca">
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button type="submit" class="btn btn-primary btn-sm float-end">Pesquisar</button>
                    </template>
                </card-component>
                <!--Fim card de busca-->
                <card-component titulo="Relação de marcas">
                    <template v-slot:conteudo>
                        <table-component></table-component>
                    </template>
                    <template v-slot:rodape>
                        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#modalMarca">Adicionar</button>
                    </template>
                </card-component>

                
                <!--Fim card de listagem de marca-->
            </div>
        </div>
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
    </div>
</template>

<script>
   export default {
         computed: {
             token(){
                
             
                 let token = document.cookie.split(';').find(indice => {
                    // console.log(indice,indice.includes('token='))
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
               arquivoImagem: [],
               transacaoStatus: '',
               transacaoDetalhes: {},
           }
       },
       methods: {
         
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
       }
   }
    
</script>