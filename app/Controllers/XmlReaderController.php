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
        $cpfs = null;
        if (isset($_POST['cpfs']))
            $cpfs = explode(',', $_POST['cpfs']);

        foreach ($_FILES as $xml) {    
            $xml_content = file_get_contents($xml['tmp_name']);

            $xml_content = trim($xml_content);

            $xml_reader = simplexml_load_string($xml_content);
            $xml_content_array = json_decode(json_encode($xml_reader), true);

            if ($cpfs != null && !in_array($xml_content_array["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["trabalhador"]["cpfTrab"], $cpfs))
                continue;

            $s2200_query = $this->generate_2200_query($xml_content_array);
            $historico_query = $this->generate_historico_query($xml_content_array);
            $s2200_dependent_query = null;

            if (isset($xml_content_array["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["trabalhador"]["dependente"]))
                $s2200_dependent_query = $this->generate_2200_dependent_query($xml_content_array);

            $queries = $s2200_query . "\n\n" . $historico_query . ($s2200_dependent_query ? "\n\n" . $s2200_dependent_query : "");

            $file = fopen(__DIR__."/../../queries.txt", "a+");
            fwrite($file, "\n\n\n" . $queries);
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
        $idevento = isset($xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["@attributes"]["Id"]) ? "'".$xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["@attributes"]["Id"]."'" : "null";
        $indretif = isset($ideevento["indRetif"]) ? "'".$ideevento["indRetif"]."'" : "null";
        $nrrecibo = "null";
        if ($indretif == 2)
            $nrrecibo = isset($xml["retornoProcessamentoDownload"]["recibo"]["eSocial"]["retornoEvento"]["recibo"]["nrRecibo"]) ? "'".$xml["retornoProcessamentoDownload"]["recibo"]["eSocial"]["retornoEvento"]["recibo"]["nrRecibo"]."'" : "null";
        $tpamb = isset($ideevento["tpAmb"]) ? "'".$ideevento["tpAmb"] : "null";
        $procemi = isset($ideevento["procEmi"]) ? "'".$ideevento["procEmi"]."'" : "null";
        $verproc = isset($ideevento["verProc"]) ? "'".$ideevento["verProc"]."'" : "null";
        $tpinsc = isset($ideempregador["tpInsc"]) ?  "'".$ideempregador["tpInsc"]."'" : "null";
        $nrinsc = isset($ideempregador["nrInsc"]) ? "'".$ideempregador["nrInsc"]."'" : "null";
        $cpftrab = isset($trabalhador["cpfTrab"]) ? "'".$trabalhador["cpfTrab"]."'" : "null";
        $nmtrab = isset($trabalhador["nmTrab"]) ? "'".$trabalhador["nmTrab"]."'" : "null";
        $sexo = isset($trabalhador["sexo"]) ? "'".$trabalhador["sexo"]."'" : "null";
        $racacor = isset($trabalhador["racaCor"]) ? "'".$trabalhador["racaCor"]."'" : "null";
        $estciv = isset($trabalhador["estCiv"]) ? "'".$trabalhador["estCiv"]."'" : "null";
        $grauinstr = isset($trabalhador["grauInstr"]) ? "'".$trabalhador["grauInstr"]."'" : "null";
        $dtnascto = isset($trabalhador["nascimento"]["dtNascto"]) ? "'".$trabalhador["nascimento"]["dtNascto"]."'" : "null";
        $paisnascto = isset($trabalhador["nascimento"]["paisNascto"]) ? "'".$trabalhador["nascimento"]["paisNascto"]."'" : "null";
        $paisnac = isset($trabalhador["nascimento"]["paisNac"]) ? "'".$trabalhador["nascimento"]["paisNac"]."'" : "null";
        $tplograd = isset($trabalhador["endereco"]["brasil"]["tpLograd"]) ? "'".$trabalhador["endereco"]["brasil"]["tpLograd"]."'" : "null";
        $dsclograd = isset($trabalhador["endereco"]["brasil"]["dscLograd"]) ? "'".$trabalhador["endereco"]["brasil"]["dscLograd"]."'" : "null";
        $nrlograd = isset($trabalhador["endereco"]["brasil"]["nrLograd"]) ? "'".$trabalhador["endereco"]["brasil"]["nrLograd"]."'" : "null";
        $bairro = isset($trabalhador["endereco"]["brasil"]["bairro"]) ? "'".$trabalhador["endereco"]["brasil"]["bairro"]."'" : "null";
        $cep = isset($trabalhador["endereco"]["brasil"]["cep"]) ? "'".$trabalhador["endereco"]["brasil"]["cep"]."'" : "null";
        $codmunic = isset($trabalhador["endereco"]["brasil"]["codMunic"]) ? "'".$trabalhador["endereco"]["brasil"]["codMunic"]."'" : "null";
        $uf = isset($trabalhador["endereco"]["brasil"]["uf"]) ? "'".$trabalhador["endereco"]["brasil"]["uf"]."'" : "null";
        $deffisica = isset($trabalhador["infoDeficiencia"]["defFisica"]) ? "'".$trabalhador["infoDeficiencia"]["defFisica"]."'" : "null"; 
        $defvisual = isset($trabalhador["infoDeficiencia"]["defVisual"]) ? "'".$trabalhador["infoDeficiencia"]["defVisual"]."'" : "null";
        $defauditiva = isset($trabalhador["infoDeficiencia"]["defAuditiva"]) ? "'".$trabalhador["infoDeficiencia"]["defAuditiva"]."'" : "null";
        $defmental = isset($trabalhador["infoDeficiencia"]["defMental"]) ? "'".$trabalhador["infoDeficiencia"]["defMental"]."'" : "null";
        $defintelectual = isset($trabalhador["infoDeficiencia"]["defIntelectual"]) ? "'".$trabalhador["infoDeficiencia"]["defIntelectual"]."'" : "null";
        $reabreadap = isset($trabalhador["infoDeficiencia"]["reabReadap"]) ? "'".$trabalhador["infoDeficiencia"]["reabReadap"]."'" : "null";
        $matricula = isset($vinculo["matricula"]) ? "'".$vinculo["matricula"]."'" : "null";
        $tpregtrab = isset($vinculo["tpRegTrab"]) ? "'".$vinculo["tpRegTrab"]."'" : "null";
        $tpregprev = isset($vinculo["tpRegPrev"]) ? "'".$vinculo["tpRegPrev"]."'" : "null";
        $cadini = isset($vinculo["cadIni"]) ? "'".$vinculo["cadIni"]."'" : "null";
        $tpprov = isset($vinculo["infoRegimeTrab"]["infoEstatutario"]["tpProv"]) ? "'".$vinculo["infoRegimeTrab"]["infoEstatutario"]["tpProv"]."'" : "null";
        $dtexercicio = isset($vinculo["infoRegimeTrab"]["infoEstatutario"]["dtExercicio"]) ? "'".$vinculo["infoRegimeTrab"]["infoEstatutario"]["dtExercicio"]."'" : "null";
        $nmcargo = isset($vinculo["infoContrato"]["nmCargo"]) ? "'".$vinculo["infoContrato"]["nmCargo"]."'" : "null";
        $cbocargo = isset($vinculo["infoContrato"]["CBOCargo"]) ? "'".$vinculo["infoContrato"]["CBOCargo"]."'" : "null";
        $nmfuncao = isset($vinculo["infoContrato"]["nmFuncao"]) ? "'".$vinculo["infoContrato"]["nmFuncao"]."'" : "null";
        $cbofuncao = isset($vinculo["infoContrato"]["CBOFuncao"]) ? "'".$vinculo["infoContrato"]["CBOFuncao"]."'" : "null";
        $acumcargo = isset($vinculo["infoContrato"]["acumCargo"]) ? "'".$vinculo["infoContrato"]["acumCargo"]."'" : "null";
        $codcateg = isset($vinculo["infoContrato"]["codCateg"]) ? "'".$vinculo["infoContrato"]["codCateg"]."'" : "null";
        $tpinsc_localtrabgeral = isset($vinculo["infoContrato"]["localTrabalho"]["localTrabGeral"]["tpInsc"]) ? "'".$vinculo["infoContrato"]["localTrabalho"]["localTrabGeral"]["tpInsc"]."'" : "null";
        $nrinsc_localtrabgeral = isset($vinculo["infoContrato"]["localTrabalho"]["localTrabGeral"]["nrInsc"]) ? "'".$vinculo["infoContrato"]["localTrabalho"]["localTrabGeral"]["nrInsc"]."'" : "null";

        $complemento = "null";
        $nmsoc = "null";
        $tmpresid = "null";
        $conding = "null";
        $infocota = "null";
        $observacao_infodeficiencia = "null";
        $foneprinc = "null";
        $emailprinc = "null";
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
        $tpplanrp = "null";
        $indtetorgps = "null";
        $indabonoperm = "null";
        $dtiniabono = "null";
        $dtingrcargo = "null";
        $vrsalfx = "null";
        $undsalfixo = "null";
        $dscsalvar = "null";
        $tpcontr = "null";
        $dtterm = "null";
        $clauassec = "null";
        $objdet = "null";
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
        $matanotjud = "null";
        $indaprend = "null";
        $cnpjentqual = "null";
        $cnpjprat = "null";

        //CAMPOS FIXOS
        $situacao = 1;
        $tipo = "'I'";
        $criado_por = 1;
        $alterado_por = 1;
    
        $query = "INSERT INTO esocial.s2200 (idevento, indretif, nrrecibo, tpamb, procemi, verproc, tpinsc, nrinsc, cpftrab, nmtrab, sexo, racacor, estciv, grauinstr, nmsoc, dtnascto, paisnascto, paisnac, tplograd, dsclograd, nrlograd, complemento, bairro, cep, codmunic, uf, tmpresid, conding, deffisica, defvisual, defauditiva, defmental, defintelectual, reabreadap, infocota, observacao_infodeficiencia, foneprinc, emailprinc, matricula, tpregtrab, tpregprev, cadini, dtadm, tpadmissao, indadmissao, nrproctrab, tpregjor, natatividade, dtbase, cnpjsindcategprof, dtopcfgts, hipleg, justcontr, tpinsc_ideestabvinc, nrinsc_ideestabvinc, tpinsc_aprend, nrinsc_aprend, tpprov, dtexercicio, tpplanrp, indtetorgps, indabonoperm, dtiniabono, nmcargo, cbocargo, dtingrcargo, nmfuncao, cbofuncao, acumcargo, codcateg, vrsalfx, undsalfixo, dscsalvar, tpcontr, dtterm, clauassec, objdet, tpinsc_localtrabgeral, nrinsc_localtrabgeral, desccomp_localtrabgeral, tplograd_localtempdom, dsclograd_localtempdom, nrlograd_localtempdom, complemento_localtempdom, bairro_localtempdom, cep_localtempdom, codmunic_localtempdom, uf_localtempdom, qtdhrssem, tpjornada, tmpparc, hornoturno, dscjorn, nrprocjud, tpinsc_sucessaovinc, nrinsc_sucessaovinc, matricant_sucessaovinc, dttransf_sucessaovinc, observacao_sucessaovinc, cpfant, matricant, dtaltcpf, observacao_mudancacpf, dtiniafast, codmotafast, dtdeslig, dtinicessao, situacao, tipo, criado_por, alterado_por, matanotjud, indaprend, cnpjentqual, cnpjprat)\n"
            . "VALUES($idevento, $indretif, $nrrecibo, $tpamb, $procemi, $verproc, $tpinsc, $nrinsc, $cpftrab, $nmtrab, $sexo, $racacor, $estciv, $grauinstr, $nmsoc, $dtnascto, $paisnascto, $paisnac, $tplograd, $dsclograd, $nrlograd, $complemento, $bairro, $cep, $codmunic, $uf, $tmpresid, $conding, $deffisica, $defvisual, $defauditiva, $defmental, $defintelectual, $reabreadap, $infocota, $observacao_infodeficiencia, $foneprinc, $emailprinc, $matricula, $tpregtrab, $tpregprev, $cadini, $dtadm, $tpadmissao, $indadmissao, $nrproctrab, $tpregjor, $natatividade, $dtbase, $cnpjsindcategprof, $dtopcfgts, $hipleg, $justcontr, $tpinsc_ideestabvinc, $nrinsc_ideestabvinc, $tpinsc_aprend, $nrinsc_aprend, $tpprov, $dtexercicio, $tpplanrp, $indtetorgps, $indabonoperm, $dtiniabono, $nmcargo, $cbocargo, $dtingrcargo, $nmfuncao, $cbofuncao, $acumcargo, $codcateg, $vrsalfx, $undsalfixo, $dscsalvar, $tpcontr, $dtterm, $clauassec, $objdet, $tpinsc_localtrabgeral, $nrinsc_localtrabgeral, $desccomp_localtrabgeral, $tplograd_localtempdom, $dsclograd_localtempdom, $nrlograd_localtempdom, $complemento_localtempdom, $bairro_localtempdom, $cep_localtempdom, $codmunic_localtempdom, $uf_localtempdom, $qtdhrssem, $tpjornada, $tmpparc, $hornoturno, $dscjorn, $nrprocjud, $tpinsc_sucessaovinc, $nrinsc_sucessaovinc, $matricant_sucessaovinc, $dttransf_sucessaovinc, $observacao_sucessaovinc, $cpfant, $matricant, $dtaltcpf, $observacao_mudancacpf, $dtiniafast, $codmotafast, $dtdeslig, $dtinicessao, $situacao, $tipo, $criado_por, $alterado_por, $matanotjud, $indaprend, $cnpjentqual, $cnpjprat);";
    
        return $query;
    }

    private function generate_historico_query($xml)
    {
        $idevento = isset($xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["@attributes"]["Id"]) ? "'".$xml["retornoProcessamentoDownload"]["evento"]["eSocial"]["evtAdmissao"]["@attributes"]["Id"]."'" : "null";
        $protocolo = isset($xml["retornoProcessamentoDownload"]["recibo"]["eSocial"]["retornoEvento"]["recepcao"]["protocoloEnvioLote"]) ? "'".$xml["retornoProcessamentoDownload"]["recibo"]["eSocial"]["retornoEvento"]["recepcao"]["protocoloEnvioLote"]."'" : "null";
        $cnpj = isset($_POST['cnpj']) ? "'".$_POST['cnpj']."'" : "null";
        $nr_recibo = isset($xml["retornoProcessamentoDownload"]["recibo"]["eSocial"]["retornoEvento"]["recibo"]["nrRecibo"]) ? "'".$xml["retornoProcessamentoDownload"]["recibo"]["eSocial"]["retornoEvento"]["recibo"]["nrRecibo"]."'" : "null";
        
        //CAMPOS FIXOS
        $evento = "'"."S2200"."'";
        $status = "'"."P"."'";
        $criado_por = 1;
        $alterado_por = 1;
        $message = "'201 - Lote processado com sucesso.  - '";

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


        $tpdep = isset($dependente["tpDep"]) ? "'".$dependente["tpDep"]."'" : "null";
        $nmdep = isset($dependente["nmDep"]) ? "'".$dependente["nmDep"]."'" : "null";
        $dtnascto = isset($dependente["dtNascto"]) ? "'".$dependente["dtNascto"]."'" : "null";
        $cpfdep = isset($dependente["cpfDep"]) ? "'".$dependente["cpfDep"]."'" : "null";
        $sexodep = "null";
        $descrdep = "null";
        $depirrf = isset($dependente["depIRRF"]) ? "'".$dependente["depIRRF"]."'" : "null";
        $depsf = isset($dependente["depSF"]) ? "'".$dependente["depSF"]."'" : "null";
        $inctrab = isset($dependente["incTrab"]) ? "'".$dependente["incTrab"]."'" : "null";
        
        //CAMPOS FIXOS
        $criado_por = 1;
        $alterado_por = 1;
        
        $query = "INSERT INTO esocial.s2200_dependente (tpdep, nmdep, dtnascto, cpfdep, sexodep, depirrf, depsf, inctrab, s2200_id, criado_por, alterado_por, descrdep) "
            . "VALUES($tpdep, $nmdep, $dtnascto, $cpfdep, $sexodep, $depirrf, $depsf, $inctrab, (SELECT id FROM esocial.s2200 s WHERE s.matricula = $matricula), $criado_por, $alterado_por, $descrdep);";
    
        return $query;
    }

}