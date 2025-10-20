<?php

namespace Alyssoncpc\QueryGenerator\Controllers;

use Alyssoncpc\QueryGenerator\Helpers\View;

class XmlReaderFormController extends Controller {

    public function render()
    {
        View::render('xml-reader-form');
    }

    public function generateQuery()
    {
        foreach ($_FILES as $xml) {    
            $xml_content = file_get_contents($xml['tmp_name']);

            $xml_content = trim($xml_content);

            $xml_reader = simplexml_load_string($xml_content);
            $xml_content_array = json_decode(json_encode($xml_reader), true);

            $s2200_query = $this->generate_2200_query($xml_content_array);
            $historico_query = $this->generate_historico_query($xml_content_array);
            $s2200_dependent_query = $this->generate_2200_dependent_query($xml_content_array);

            $file = fopen(__DIR__."/../../queries.txt", "a+");
            fwrite($file, "\n\n\n\n" . $historico_query . "\n\n" . $s2200_query . "\n\n" . $s2200_dependent_query);
            fclose($file);
        }
    }

    private function generate_2200_query($xml)
    {
        $ideevento = $xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["ideEvento"];
        $ideempregador = $xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["ideEmpregador"];
        $trabalhador = $xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["trabalhador"];
        $vinculo = $xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["vinculo"];

        // table cols
        $idevento = "\"".$xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["@attributes"]["Id"]."\"";
        $indretif = "\"".$ideevento["indRetif"]."\"";
        $nrrecibo = "null";
        if ($indretif == 2)
            $nrrecibo = "\"".$xml["retornoProcessamentoDownload"]["recibo"]["eSocial"]["retornoEvento"]["recibo"]["nrRecibo"]."\"";
        $tpamb = "\"".$ideevento["tpAmb"];
        $procemi = "\"".$ideevento["procEmi"]."\"";
        $verproc = "\"".$ideevento["verProc"]."\"";
        
        $tpinsc = "\"".$ideempregador["tpInsc"]."\"";
        $nrinsc = "\"".$ideempregador["nrInsc"]."\"";

        $cpftrab = "\"".$trabalhador["cpfTrab"]."\"";
        $nmtrab = "\"".$trabalhador["nmTrab"]."\"";
        $sexo = "\"".$trabalhador["sexo"]."\"";
        $racacor = "\"".$trabalhador["racaCor"]."\"";
        $estciv = "\"".$trabalhador["estCiv"]."\"";
        $grauinstr = "\"".$trabalhador["grauInstr"]."\"";
        $nmsoc = "null";
        $dtnascto = "\"".$trabalhador["nascimento"]["dtNascto"]."\"";
        $paisnascto = "\"".$trabalhador["nascimento"]["paisNascto"]."\"";
        $paisnac = "\"".$trabalhador["nascimento"]["paisNac"]."\"";
        $tplograd = "\"".$trabalhador["endereco"]["brasil"]["tpLograd"]."\"";
        $dsclograd = "\"".$trabalhador["endereco"]["brasil"]["dscLograd"]."\"";
        $nrlograd = "\"".$trabalhador["endereco"]["brasil"]["nrLograd"]."\"";
        $complemento = "null";
        $bairro = "\"".$trabalhador["endereco"]["brasil"]["bairro"]."\"";
        $cep = "\"".$trabalhador["endereco"]["brasil"]["cep"]."\"";
        $codmunic = "\"".$trabalhador["endereco"]["brasil"]["codMunic"]."\"";
        $uf = "\"".$trabalhador["endereco"]["brasil"]["uf"]."\"";
        $tmpresid = "null";
        $conding = "null";
        $deffisica = "\"".$trabalhador["infoDeficiencia"]["defFisica"]."\""; 
        $defvisual = "\"".$trabalhador["infoDeficiencia"]["defVisual"]."\"";
        $defauditiva = "\"".$trabalhador["infoDeficiencia"]["defAuditiva"]."\"";
        $defmental = "\"".$trabalhador["infoDeficiencia"]["defMental"]."\"";
        $defintelectual = "\"".$trabalhador["infoDeficiencia"]["defIntelectual"]."\"";
        $reabreadap = "\"".$trabalhador["infoDeficiencia"]["reabReadap"]."\"";
        $infocota = "null";
        $observacao_infodeficiencia = "null";
        $foneprinc = "null";
        $emailprinc = "null";

        $matricula = "\"".$vinculo["matricula"]."\"";
        $tpregtrab = "\"".$vinculo["tpRegTrab"]."\"";
        $tpregprev = "\"".$vinculo["tpRegPrev"]."\"";
        $cadini = "\"".$vinculo["cadIni"]."\"";
        $dtadm = "null";
        $tpadmissao = "null";
        $indadmissao = "null";
        $nrproctrab = "null";
        $tpregjor = "null";
        $natatividade = "null";
        $dtbase = "null";
        $cnpjsindcategprof = "null";
        $dtopcfgts = "null";
        $hipleg = "null";
        $justcontr = "null";
        $tpinsc_ideestabvinc = "null";
        $nrinsc_ideestabvinc = "null";
        $tpinsc_aprend = "null";
        $nrinsc_aprend = "null";
        $tpprov = "\"".$vinculo["infoRegimeTrab"]["infoEstatutario"]["tpProv"]."\"";
        $dtexercicio = "\"".$vinculo["infoRegimeTrab"]["infoEstatutario"]["dtExercicio"]."\"";
        $tpplanrp = "null";
        $indtetorgps = "null";
        $indabonoperm = "null";
        $dtiniabono = "null";
        $nmcargo = "\"".$vinculo["infoContrato"]["nmCargo"]."\"";
        $cbocargo = "\"".$vinculo["infoContrato"]["CBOCargo"]."\"";
        $dtingrcargo = "null";
        $nmfuncao = "\"".$vinculo["infoContrato"]["nmFuncao"]."\"";
        $cbofuncao = "\"".$vinculo["infoContrato"]["CBOFuncao"]."\"";
        $acumcargo = "\"".$vinculo["infoContrato"]["acumCargo"]."\"";
        $codcateg = "\"".$vinculo["infoContrato"]["codCateg"]."\"";
        $vrsalfx = "null";
        $undsalfixo = "null";
        $dscsalvar = "null";
        $tpcontr = "null";
        $dtterm = "null";
        $clauassec = "null";
        $objdet = "null";
        $tpinsc_localtrabgeral = "\"".$vinculo["infoContrato"]["localTrabalho"]["localTrabGeral"]["tpInsc"]."\"";
        $nrinsc_localtrabgeral = "\"".$vinculo["infoContrato"]["localTrabalho"]["localTrabGeral"]["nrInsc"]."\"";

        $desccomp_localtrabgeral = "null";
        $tplograd_localtempdom  = "null";
        $dsclograd_localtempdom = "null";
        $nrlograd_localtempdom = "null";
        $complemento_localtempdom = "null";
        $bairro_localtempdom = "null";
        $cep_localtempdom = "null";
        $codmunic_localtempdom = "null";
        $uf_localtempdom = "null";
        $qtdhrssem = "null";
        $tpjornada = "null";
        $tmpparc = "null";
        $hornoturno = "null";
        $dscjorn = "null";
        $nrprocjud = "null";
        $tpinsc_sucessaovinc = "null";
        $nrinsc_sucessaovinc = "null";
        $matricant_sucessaovinc = "null";
        $dttransf_sucessaovinc = "null";
        $observacao_sucessaovinc = "null";
        $cpfant = "null";
        $matricant = "null";
        $dtaltcpf = "null";
        $observacao_mudancacpf = "null";
        $dtiniafast = "null";
        $codmotafast = "null";
        $dtdeslig = "null";
        $dtinicessao = "null";
        $situacao = 1;
        $tipo = "\"I\"";
        $criado_por = 1;
        $alterado_por = 1;
        $matanotjud = "null";
        $indaprend = "null";
        $cnpjentqual = "null";
        $cnpjprat = "null";
    
        $query = "INSERT INTO esocial.s2200 (idevento, indretif, nrrecibo, tpamb, procemi, verproc, tpinsc, nrinsc, cpftrab, nmtrab, sexo, racacor, estciv, grauinstr, nmsoc, dtnascto, paisnascto, paisnac, tplograd, dsclograd, nrlograd, complemento, bairro, cep, codmunic, uf, tmpresid, conding, deffisica, defvisual, defauditiva, defmental, defintelectual, reabreadap, infocota, observacao_infodeficiencia, foneprinc, emailprinc, matricula, tpregtrab, tpregprev, cadini, dtadm, tpadmissao, indadmissao, nrproctrab, tpregjor, natatividade, dtbase, cnpjsindcategprof, dtopcfgts, hipleg, justcontr, tpinsc_ideestabvinc, nrinsc_ideestabvinc, tpinsc_aprend, nrinsc_aprend, tpprov, dtexercicio, tpplanrp, indtetorgps, indabonoperm, dtiniabono, nmcargo, cbocargo, dtingrcargo, nmfuncao, cbofuncao, acumcargo, codcateg, vrsalfx, undsalfixo, dscsalvar, tpcontr, dtterm, clauassec, objdet, tpinsc_localtrabgeral, nrinsc_localtrabgeral, desccomp_localtrabgeral, tplograd_localtempdom, dsclograd_localtempdom, nrlograd_localtempdom, complemento_localtempdom, bairro_localtempdom, cep_localtempdom, codmunic_localtempdom, uf_localtempdom, qtdhrssem, tpjornada, tmpparc, hornoturno, dscjorn, nrprocjud, tpinsc_sucessaovinc, nrinsc_sucessaovinc, matricant_sucessaovinc, dttransf_sucessaovinc, observacao_sucessaovinc, cpfant, matricant, dtaltcpf, observacao_mudancacpf, dtiniafast, codmotafast, dtdeslig, dtinicessao, situacao, tipo, criado_por, alterado_por, matanotjud, indaprend, cnpjentqual, cnpjprat)\n"
            . "VALUES($idevento, $indretif, $nrrecibo, $tpamb, $procemi, $verproc, $tpinsc, $nrinsc, $cpftrab, $nmtrab, $sexo, $racacor, $estciv, $grauinstr, $nmsoc, $dtnascto, $paisnascto, $paisnac, $tplograd, $dsclograd, $nrlograd, $complemento, $bairro, $cep, $codmunic, $uf, $tmpresid, $conding, $deffisica, $defvisual, $defauditiva, $defmental, $defintelectual, $reabreadap, $infocota, $observacao_infodeficiencia, $foneprinc, $emailprinc, $matricula, $tpregtrab, $tpregprev, $cadini, $dtadm, $tpadmissao, $indadmissao, $nrproctrab, $tpregjor, $natatividade, $dtbase, $cnpjsindcategprof, $dtopcfgts, $hipleg, $justcontr, $tpinsc_ideestabvinc, $nrinsc_ideestabvinc, $tpinsc_aprend, $nrinsc_aprend, $tpprov, $dtexercicio, $tpplanrp, $indtetorgps, $indabonoperm, $dtiniabono, $nmcargo, $cbocargo, $dtingrcargo, $nmfuncao, $cbofuncao, $acumcargo, $codcateg, $vrsalfx, $undsalfixo, $dscsalvar, $tpcontr, $dtterm, $clauassec, $objdet, $tpinsc_localtrabgeral, $nrinsc_localtrabgeral, $desccomp_localtrabgeral, $tplograd_localtempdom, $dsclograd_localtempdom, $nrlograd_localtempdom, $complemento_localtempdom, $bairro_localtempdom, $cep_localtempdom, $codmunic_localtempdom, $uf_localtempdom, $qtdhrssem, $tpjornada, $tmpparc, $hornoturno, $dscjorn, $nrprocjud, $tpinsc_sucessaovinc, $nrinsc_sucessaovinc, $matricant_sucessaovinc, $dttransf_sucessaovinc, $observacao_sucessaovinc, $cpfant, $matricant, $dtaltcpf, $observacao_mudancacpf, $dtiniafast, $codmotafast, $dtdeslig, $dtinicessao, $situacao, $tipo, $criado_por, $alterado_por, $matanotjud, $indaprend, $cnpjentqual, $cnpjprat);";
    
        return $query;
    }

    private function generate_historico_query($xml)
    {
        $idevento = "\"".$xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["@attributes"]["Id"]."\"";
        $evento = "\""."S2200"."\"";
        $status = "\""."P"."\"";
        $criado_por = 1;
        $alterado_por = 1;
        $message = "\"201 - Lote processado com sucesso.  - \"";
        $protocolo = "\"".$xml["retornoProcessamentoDownload"]["recibo"]["eSocial"]["retornoEvento"]["recepcao"]["protocoloEnvioLote"]."\"";
        $cnpj = "\"".$_POST['cnpj']."\"";
        $nr_recibo = "\"".$xml["retornoProcessamentoDownload"]["recibo"]["eSocial"]["retornoEvento"]["recibo"]["nrRecibo"]."\"";
        
        $insertQuery = "INSERT INTO esocial.historico (idevento, evento, status, criado_por, alterado_por, message, protocolo, cnpj, nr_recibo)\n"
            . "VALUES ($idevento, $evento, $status, $criado_por, $alterado_por, $message, $protocolo, $cnpj, $nr_recibo);";
        $updateQuery = "UPDATE esocial.historico h SET evento_id = s.id FROM esocial.s2200 s WHERE h.evento = 'S2200' AND h.idevento = s.idevento;";
        
        $query = $insertQuery . " " . $updateQuery;

        return $query;
    }

    public function generate_2200_dependent_query($xml)
    {
        $dependente = $xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["trabalhador"]["dependente"];
        $matricula = $xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["vinculo"]["matricula"];


        $tpdep = $dependente["tpDep"];
        $nmdep = $dependente["nmDep"];
        $dtnascto = $dependente["dtNascto"];
        $cpfdep = $dependente["cpfDep"];
        $sexodep = null;
        $depirrf = $dependente["depIRRF"];
        $depsf = $dependente["depSF"];
        $inctrab = $dependente["incTrab"];
        $criado_por = 1;
        $alterado_por = 1;
        $descrdep = null;

        $query = "INSERT INTO esocial.s2200_dependente (tpdep, nmdep, dtnascto, cpfdep, sexodep, depirrf, depsf, inctrab, s2200_id, criado_por, alterado_por, descrdep) "
            . "VALUES($tpdep, $nmdep, $dtnascto, $cpfdep, $sexodep, $depirrf, $depsf, $inctrab, (SELECT id FROM esocial.s2200 s WHERE s.matricula = $matricula), $criado_por, $alterado_por, $descrdep);";
    
        return $query;
    }

}