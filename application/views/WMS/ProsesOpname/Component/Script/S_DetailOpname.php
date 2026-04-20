<script>
	const urlSearchParams = new URLSearchParams(window.location.search);
	const typeOpname = Object.fromEntries(urlSearchParams.entries()).data;
	loadingBeforeReadyPage()
	$(document).ready(function() {
		$("#title-type").html(typeOpname);

		initDataCardDetail();
	});

	const initDataCardDetail = () => {
		$("#showDataDetail").empty()
		for (let index = 0; index < 10; index++) {
			$("#showDataDetail").append(`
        <div class="card-detail-opname">
          <table width="100%">
            <tbody>
              <tr>
                <td width="39%">Kode Dokumen</td>
                <td width="2%" class="text-center">:&nbsp;</td>
                <td width="59%">KNJ/SOP/20221109001</td>
              </tr>
              <tr>
                <td>Tipe Stock Opname</td>
                <td class="text-center">:&nbsp;</td>
                <td>Partial Opname</td>
              </tr>
              <tr>
                <td>Perusahaan</td>
                <td class="text-center">:&nbsp;</td>
                <td>PT. Padmatirta</td>
              </tr>
              <tr>
                <td>Principle</td>
                <td class="text-center">:&nbsp;</td>
                <td>Nestle</td>
              </tr>
              <tr>
                <td>Jenis Stock</td>
                <td class="text-center">:&nbsp;</td>
                <td>GoodStock</td>
              </tr>
              <tr>
                <td>Status</td>
                <td class="text-center">:&nbsp;</td>
                <td>Approved</td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-right">
                  <button class="btn btn-primary btn-sm">Laksanakan</button>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      `)
		}
	}

	const handlerKembaliDetailData = () => {
		location.href = "<?= base_url('WMS/ProsesOpname/ProsesOpnameMenu') ?>";
	}
</script>