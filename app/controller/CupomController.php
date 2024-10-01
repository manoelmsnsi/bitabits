<?php class Cupom extends Controller {   
    private  $auth,$db;
    public function acesso_restrito(){          
        $this->auth = new AutenticaHelper();
        $this->auth->setLoginControllerAction('Index','')
                   ->checkLogin('redirect');              
        $this->db = new AdminModel(); 
    } 

   public function nf_termica(){
        $acesso = new SessionHelper();
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        $tipo ="Receita"; //$this->getParams("tipo");
        $dados["tipo"]=$tipo;
                    $filial = new FilialModel();
                    $listar_filial = $filial->listar_Filial(NULL,NULL,"id_status<>99",NULL,' Filial.id DESC',NULL);
                    $dados['listar_filial'] = $listar_filial; 
                   
                   $tipo_documento = new TipoDocumentoModel();
                   $listar_tipo_documento = $tipo_documento->listar_TipoDocumento(NULL,NULL,"id_status<>99",NULL,' TipoDocumento.id DESC',NULL);
                    $dados['listar_tipo_documento'] = $listar_tipo_documento; 
                    
                    $tipo_pagamento = new TipoPagamentoModel();
                    $listar_tipo_pagamento = $tipo_pagamento->listar_TipoPagamento(NULL,NULL,"id_status<>99",NULL,' TipoPagamento.id DESC',NULL);
                    $dados['listar_tipo_pagamento'] = $listar_tipo_pagamento;                    
                    $plano_contas = new PlanoContasModel();
                    $listar_plano_conta = $plano_contas->listar_PlanoContas(NULL,NULL,"id_status<>99",NULL,' PlanoContas.id DESC',NULL);
                    $dados['listar_plano_conta'] = $listar_plano_conta;           
                    if($tipo=="Receita"){
                        $pessoa = new PessoaModel();
                        $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='Cliente'",NULL,' Pessoa.id DESC',NULL);
                        $dados['listar_cliente'] = $listar_pessoa; 
                    }else{
                        $pessoa = new PessoaModel();
                        $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='Fornecedor'",NULL,' Pessoa.id DESC',NULL);
                        $dados['listar_cliente'] = $listar_pessoa;  
                    }
                    
                   $conta_bancaria = new ContaBancariaModel();
                    $listar_conta_bancaria = $conta_bancaria->listar_ContaBancaria(NULL,NULL,"id_status<>99",NULL,' ContaBancaria.id DESC',NULL);
                    $dados['listar_conta_bancaria'] = $listar_conta_bancaria; 
                    $pessoa = new PessoaModel();
                    $listar_pessoa = $pessoa->listar_Pessoa(NULL,NULL,"id_status<>'99' AND Pessoa.tipo='colaborador'",NULL,' Pessoa.id DESC',NULL);
                    $dados['listar_colaborador'] = $listar_pessoa; 
                    
                    $status = new StatusModel();
                    $listar_status = $status->listar_Status(NULL,NULL,"id_status<>99 AND (Status.tabela='Venda' OR Status.tabela='Geral')",NULL,' Status.id ASC',NULL);
                    $dados['listar_status'] = $listar_status;  

        $id = $this->getParams('id');
        $dados['id']=$id;
        
        $pago = $this->getParams('pago');
        $dados['total_pago']=$pago;
        if(!empty($id)){
            $itens = new ItensModel();
            $listar_itens = $itens->listar_Itens("INNER JOIN Produto ON Produto.id = Itens.id_produto",NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Venda'",NULL,' Itens.id DESC',"Produto.id AS id_produto,Produto.descricao,Itens.id,Produto.codigo_barra,Itens.quantidade,Itens.valor_venda");
            $dados['listar_itens'] = $listar_itens;      
            
            $listar_servico = $itens->listar_Itens("INNER JOIN Produto ON Produto.id = Itens.id_produto",NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Servico'",NULL,' Itens.id DESC',"Produto.id AS id_produto,Produto.descricao,Itens.id,Produto.codigo_barra,Itens.quantidade,Itens.valor_venda");        
            $dados['listar_servico'] = $listar_servico; 
            
            $listar_outros = $itens->listar_Itens(NULL,NULL,"Itens.id_status<>99 AND id_tabela='{$id}' AND tabela='Outros'",NULL,' Itens.id DESC',"id,descricao,Itens.id,Itens.quantidade,Itens.valor_venda");        
            $dados['listar_outros'] = $listar_outros; 
            
            $venda = new VendaModel();
            $listar_venda = $venda->listar_venda(NULL,NULL,"id=$id AND id_status='1'",NULL,'Venda.id DESC');
            $dados['listar_venda'] = $listar_venda;
        }     
        $this->view("form_cupom",$dados);           
    } 
    
    public function receituario_form(){
      
        $menu = new MenuHelper("B", $Class, $AcaoForm, $MetodoDeEnvio);        
        echo $menu->Menu();
        $acesso = new SessionHelper();
        $userFilial=$acesso->selectSession('userFilial');
        $id=$this->getParams("id");
        $tabela= $this->getParams("tabela");
        $tabela1=$tabela;
        $tabela=$tabela."Model";
        $dados = new $tabela();
        $lista="listar_$tabela1";
        $dados = $dados->$lista($join, $limit, "id='$id'", $offset, $orderby, $from, $group, $pesquisa);
      $form = new FormularioHelper();
 echo'<style>
    #timbrado {
    padding: 30px;
}

.timbrado {
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px;
}

.timbrado header {
    padding: 10px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #3989c6;
}

.timbrado .company-details {
    text-align: right;
}

.timbrado .company-details .name {
    margin-top: 0;
    margin-bottom: 0;
}

.timbrado .contacts {
    margin-bottom: 20px;
}

.timbrado .timbrado-to {
    text-align: left;
}

.timbrado .timbrado-to .to {
    margin-top: 0;
    margin-bottom: 0;
}

.timbrado .timbrado-details {
    text-align: right;
}

.timbrado .timbrado-details .timbrado-id {
    margin-top: 0;
    color: #3989c6;
}

.timbrado main {
    padding-bottom: 50px;
}

.timbrado main .thanks {
    margin-top: -100px;
    font-size: 2em;
    margin-bottom: 50px;
}

.timbrado main .notices {
    padding-left: 6px;
    border-left: 6px solid #3989c6;
}

.timbrado main .conteudo {
    padding-left: 6px;
    margin-bottom: 2rem;
    margin-top: 2rem;
}

.timbrado main .notices .notice {
    font-size: 1em;
}

.timbrado table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px;
}

.timbrado table td,
.timbrado table th {
    padding: 15px;
    background: #eee;
    border-bottom: 1px solid #fff;
}

.timbrado table th {
    white-space: nowrap;
    font-weight: 400;
    font-size: 16px;
}

.timbrado table td h3 {
    margin: 0;
    font-weight: 400;
    color: #3989c6;
    font-size: 1.2em;
}

.timbrado table .qty,
.timbrado table .total,
.timbrado table .unit {
    text-align: right;
    font-size: 1.2em;
}

.timbrado table .no {
    color: #fff;
    font-size: 1.6em;
    background: #3989c6;
}

.timbrado table .unit {
    background: #ddd;
}

.timbrado table .total {
    background: #3989c6;
    color: #fff;
}

.timbrado table tbody tr:last-child td {
    border: none;
}

.timbrado table tfoot td {
    background: 0 0;
    border-bottom: none;
    white-space: nowrap;
    text-align: right;
    padding: 10px 20px;
    font-size: 1.2em;
    border-top: 1px solid #aaa;
}

.timbrado table tfoot tr:first-child td {
    border-top: none;
}

.timbrado table tfoot tr:last-child td {
    color: #3989c6;
    font-size: 1.4em;
    border-top: 1px solid #3989c6;
}

.timbrado table tfoot tr td:first-child {
    border: none;
}

.timbrado footer {
    width: 100%;
    text-align: center;
    color: #777;
    border-top: 1px solid #aaa;
    padding: 8px 0;
}

@media print {
    .timbrado {
        font-size: 11px!important;
        overflow: hidden!important;
    }

    .timbrado footer {
        position: absolute;
        bottom: 10px;
        page-break-after: always;
    }

    .timbrado>div:last-child {
        page-break-before: always;
    }
}
      
         </style>';
               echo  '<div class="container">
                        <div id="timbrado">
                            <div class="toolbar hidden-print">
                                <div class="text-right">
                                    <button id="printtimbrado" class="btn btn-info">
                                        <i class="fa fa-print"></i> Imprimir
                                    </button>
                                    <button class="btn btn-info">
                                        <i class="fa fa-file-pdf-o"></i> Exportar como PDF
                                    </button>
                                </div>
                                <hr>
                            </div>
                            <div class="timbrado overflow-auto">
                                <div style="min-width: 600px">
                                    <header>
                                        <div class="row">
                                            <div class="col">
                                                <a target="_blank" href="#"><img  style="widht:100px;height:70px;" src="/web-files/sistema/imagens/univale.png" data-holder-rendered="true" width="350"/> </a>
                                            </div>
                                            <div class="col company-details">
                                                <h2 class="name"> <a target="_blank" href="#">'.$userFilial[0]["nome_fantasia"].'</a> </h2>
                                                <div>Rua Café Filho, 000</div>
                                                <div>(69) 3461-0000</div>
                                                <div>contato@redeuniclinica.com</div>
                                            </div>
                                        </div>
                                    </header>
                                    <main>
                                        <div class="conteudo">
                                            <p>'.$dados[0]["texto"].'</p>
                                        </div>
                                        <div class="notices">
                                            <div>
                                                <h6> 
                                                Observações</h6>
                                            </div>
                                            <div class="notice">Nada a constar.</div>
                                        </div>
                                    </main>
                                    <footer>'.date("d/m/Y H:m:s").'</footer>
                                </div>
                                <div></div>
                            </div>
                        </div>
                    </div>';
      
      //$form->card($nome_form,$inputs,"col-md-6",$comando,"POST",$icone);
    }
}?>  