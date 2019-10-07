							<article id="register">
								<h2 class="major">Register</h2>
								<form method="post" action="<?= base_url('auth/registration'); ?>">
									<div class="fields" >
										<div class="field half">
											<label for="name">Username</label>
											<input type="text" name="name" id="name" placeholder="Full Name" />
											
										</div>
										<div class="field half">
											<label for="email">Password</label>
											<input type="password" name="password1" id="password1" placeholder="Password" />

										</div>
										</div>
										<div class="field half">
											<label for="email">Re-Type Password</label>
											<input type="password" name="password2" id="password2" placeholder="Re-Type Password" /> <br>
										</div>
										<ul class="actions">
												<li><input type="submit" value="Register Account" class="primary" /></li>
											</ul>


								</form>