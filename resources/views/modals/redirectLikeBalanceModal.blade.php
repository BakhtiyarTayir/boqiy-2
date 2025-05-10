<div class="modal"
     id="redirectLikeBalanceModal"
     tabindex="-1"
     role="dialog"
     aria-labelledby="redirectLikeBalanceModalLabel"
     aria-modal="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="redirectLikeBalanceModalLabel" class="modal-title">
					<i class="fas fa-circle-exclamation text-yellow-500 text-2xl"></i>
					Balans yetarli emas
				</h5>
			</div>
			<div class="modal-body">
				<p>
					Sizning hisobingizda ushbu amalni bajarish uchun yetarli mablag‘ mavjud emas. Iltimos, hisobingizni to‘ldiring yoki boshqa amal tanlang.
				</p>
			</div>
			<div class="modal-footer">
				<a href="{{ route('like_balance.topup') }}" class="btn btn-primary">
					Hisobni to'ldirish
				</a>
				<button type="button" class="btn  btn-secondary " data-bs-dismiss="modal">
					Close
				</button>
			</div>
		</div>
	</div>
</div>