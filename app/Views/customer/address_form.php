<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$address = $address ?? [];
$errors  = $errors ?? [];
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-4 p-md-5">
          <h4 class="mb-3">Add Delivery Address</h4>
          <p class="text-muted small mb-3">
            Save a delivery address for faster checkout. You can add multiple addresses.
          </p>

          <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger small">
              <?= esc(session()->getFlashdata('error')) ?>
            </div>
          <?php endif; ?>

          <form action="<?= site_url('customer/addresses/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name"
                       class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                       value="<?= esc($address['name'] ?? '') ?>"
                       placeholder="Receiver's full name">
                <?php if (isset($errors['name'])): ?>
                  <div class="invalid-feedback"><?= esc($errors['name']) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="phone"
                       class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                       value="<?= esc($address['phone'] ?? '') ?>"
                       placeholder="10-digit mobile">
                <?php if (isset($errors['phone'])): ?>
                  <div class="invalid-feedback"><?= esc($errors['phone']) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label">Alternate Mobile (optional)</label>
                <input type="text" name="alternate_phone"
                       class="form-control"
                       value="<?= esc($address['alternate_phone'] ?? '') ?>">
              </div>

              <div class="col-12">
                <label class="form-label">Address Line 1</label>
                <input type="text" name="address_line1"
                       class="form-control <?= isset($errors['address_line1']) ? 'is-invalid' : '' ?>"
                       value="<?= esc($address['address_line1'] ?? '') ?>"
                       placeholder="House / Flat / Building / Street">
                <?php if (isset($errors['address_line1'])): ?>
                  <div class="invalid-feedback"><?= esc($errors['address_line1']) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-12">
                <label class="form-label">Address Line 2 (optional)</label>
                <input type="text" name="address_line2"
                       class="form-control"
                       value="<?= esc($address['address_line2'] ?? '') ?>"
                       placeholder="Area / Locality">
              </div>

              <div class="col-md-6">
                <label class="form-label">Landmark (optional)</label>
                <input type="text" name="landmark"
                       class="form-control"
                       value="<?= esc($address['landmark'] ?? '') ?>"
                       placeholder="Near temple, mall, etc.">
              </div>

              <div class="col-md-4">
                <label class="form-label">City</label>
                <input type="text" name="city"
                       class="form-control <?= isset($errors['city']) ? 'is-invalid' : '' ?>"
                       value="<?= esc($address['city'] ?? '') ?>">
                <?php if (isset($errors['city'])): ?>
                  <div class="invalid-feedback"><?= esc($errors['city']) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-md-4">
                <label class="form-label">State</label>
                <input type="text" name="state"
                       class="form-control <?= isset($errors['state']) ? 'is-invalid' : '' ?>"
                       value="<?= esc($address['state'] ?? '') ?>">
                <?php if (isset($errors['state'])): ?>
                  <div class="invalid-feedback"><?= esc($errors['state']) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-md-4">
                <label class="form-label">Pincode</label>
                <input type="text" name="pincode"
                       class="form-control <?= isset($errors['pincode']) ? 'is-invalid' : '' ?>"
                       value="<?= esc($address['pincode'] ?? '') ?>">
                <?php if (isset($errors['pincode'])): ?>
                  <div class="invalid-feedback"><?= esc($errors['pincode']) ?></div>
                <?php endif; ?>
              </div>

              <div class="col-md-6">
                <label class="form-label">Country</label>
                <input type="text" name="country"
                       class="form-control"
                       value="<?= esc($address['country'] ?? 'India') ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label">Address Type</label>
                <select name="address_type" class="form-select">
                  <?php $type = $address['address_type'] ?? 'home'; ?>
                  <option value="home"   <?= $type === 'home' ? 'selected' : '' ?>>Home</option>
                  <option value="office" <?= $type === 'office' ? 'selected' : '' ?>>Office</option>
                  <option value="other"  <?= $type === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
              </div>

              <div class="col-12 d-flex flex-column flex-md-row align-items-md-center justify-content-between mt-2 gap-2">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="is_default" id="isDefault"
                         value="1" <?= !empty($address['is_default']) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="isDefault">
                    Make this my default delivery address
                  </label>
                </div>

                <!-- Use current location -->
                <button type="button" class="btn btn-sm btn-outline-success" id="useCurrentLocationBtn">
                  <i class="far fa-location-crosshairs me-1"></i>
                  Use Current Location
                </button>
              </div>

              <!-- Hidden fields for lat/lng -->
              <input type="hidden" name="latitude" id="latitude"
                     value="<?= esc($address['latitude'] ?? '') ?>">
              <input type="hidden" name="longitude" id="longitude"
                     value="<?= esc($address['longitude'] ?? '') ?>">

              <div class="col-12 small text-muted mt-1" id="locationStatus"></div>

              <div class="col-12 d-flex justify-content-between align-items-center mt-4">
                <a href="<?= site_url('customer/addresses') ?>" class="btn btn-outline-secondary">
                  Cancel
                </a>
                <button type="submit" class="btn btn-success">
                  Save Address
                </button>
              </div>
            </div> <!-- /.row -->

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('useCurrentLocationBtn');
  const statusEl = document.getElementById('locationStatus');
  const latInput = document.getElementById('latitude');
  const lngInput = document.getElementById('longitude');

  if (!btn) return;

  btn.addEventListener('click', function() {
    if (!navigator.geolocation) {
      statusEl.textContent = 'Geolocation is not supported by your browser.';
      return;
    }

    statusEl.textContent = 'Getting your current location...';

    navigator.geolocation.getCurrentPosition(
      function(pos) {
        const lat = pos.coords.latitude.toFixed(7);
        const lng = pos.coords.longitude.toFixed(7);
        latInput.value = lat;
        lngInput.value = lng;
        statusEl.textContent = 'Location captured. Latitude: ' + lat +
          ', Longitude: ' + lng +
          '. You can still adjust the address fields if needed.';
      },
      function(err) {
        statusEl.textContent = 'Could not get location: ' + err.message;
      },
      {
        enableHighAccuracy: true,
        timeout: 10000
      }
    );
  });
});
</script>

<?= $this->endSection() ?>