<?php
echo json_encode(\Spatie\Permission\Models\Permission::all()->pluck('name'));
